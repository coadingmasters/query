/**
 * A one page, branded PDF report for the cat calorie calculator.
 *
 * Written against the PDF format directly rather than pulled from a library.
 * Two reasons: the page promises the calculator runs entirely in the browser
 * and sends nothing anywhere, which a server-rendered PDF would quietly break,
 * and the smallest client-side PDF library is larger than this whole page's
 * JavaScript budget for a feature most visitors never touch.
 *
 * PDF puts its origin at the bottom left and measures in points, 72 to the
 * inch. Every y below is therefore a distance up from the bottom of an A4
 * page, which is why the layout is written top down with a cursor that
 * decreases.
 */

const PAGE = { w: 595.28, h: 841.89 };
const MARGIN = 48;
const CONTENT_W = PAGE.w - MARGIN * 2;

// The brand palette, as PDF's 0-1 RGB rather than hex.
const COLOR = {
    coral: [0.957, 0.486, 0.42],      // #F47C6B
    coralLight: [0.988, 0.891, 0.867], // #FCE3DD
    ink: [0.071, 0.22, 0.231],         // #12383B
    inkMuted: [0.322, 0.396, 0.408],   // #526568
    white: [1, 1, 1],
    line: [0.929, 0.906, 0.882],       // #EDE7E1
    sage: [0.31, 0.424, 0.286],        // #4F6C49
    sageLight: [0.91, 0.941, 0.894],   // #E8F0E4
    amber: [0.576, 0.361, 0.043],      // #935C0B
    amberLight: [0.992, 0.953, 0.886], // #FDF3E2
};

/**
 * PDF's standard fonts are WinAnsi encoded, so anything outside Latin-1 has
 * to be folded down before it reaches the file. The characters that actually
 * turn up here are typographic: the en dash in "Adult (3-7 years)", curly
 * quotes from a pasted cat name, the multiplication sign in the breakdown.
 */
function winAnsi(text) {
    return String(text ?? '')
        .replace(/[–—]/g, '-')
        .replace(/[‘’]/g, "'")
        .replace(/[“”]/g, '"')
        .replace(/…/g, '...')
        .replace(/[×✕]/g, 'x')
        .replace(/±/g, '+/-')
        .replace(/[^\x20-\x7E\xA0-\xFF]/g, '');
}

/** Escapes the three characters that mean something inside a PDF string. */
function pdfString(text) {
    return winAnsi(text).replace(/([\\()])/g, '\\$1');
}

/** Widths of Helvetica at 1pt, indexed by char code, for centering and wrapping. */
const HELVETICA_WIDTHS = (() => {
    // The standard 14 metrics. Only the printable ASCII range is spelled out;
    // everything above falls back to the average, which is close enough for
    // the accented characters a cat name might carry.
    const w = new Array(256).fill(556);
    const set = (from, chars, width) => {
        for (const c of chars) w[c.charCodeAt(0)] = width;
    };
    set(0, ' ', 278); set(0, '!', 278); set(0, '"', 355); set(0, '#', 556);
    set(0, '$', 556); set(0, '%', 889); set(0, '&', 667); set(0, "'", 191);
    set(0, '(', 333); set(0, ')', 333); set(0, '*', 389); set(0, '+', 584);
    set(0, ',', 278); set(0, '-', 333); set(0, '.', 278); set(0, '/', 278);
    for (const c of '0123456789') w[c.charCodeAt(0)] = 556;
    set(0, ':', 278); set(0, ';', 278); set(0, '<', 584); set(0, '=', 584);
    set(0, '>', 584); set(0, '?', 556); set(0, '@', 1015);
    set(0, 'ABDEHKNOPQRSUVXY', 722);
    set(0, 'C', 722); set(0, 'G', 778); set(0, 'M', 833); set(0, 'W', 944);
    set(0, 'F', 611); set(0, 'I', 278); set(0, 'J', 500); set(0, 'L', 556);
    set(0, 'T', 611); set(0, 'Z', 611);
    set(0, '[', 278); set(0, '\\', 278); set(0, ']', 278); set(0, '^', 469);
    set(0, '_', 556); set(0, '`', 333);
    set(0, 'abcdeghnopqu', 556);
    set(0, 'f', 278); set(0, 'i', 222); set(0, 'j', 222); set(0, 'k', 500);
    set(0, 'l', 222); set(0, 'm', 833); set(0, 'r', 333); set(0, 's', 500);
    set(0, 't', 278); set(0, 'v', 500); set(0, 'w', 722); set(0, 'x', 500);
    set(0, 'y', 500); set(0, 'z', 500);
    set(0, '{', 334); set(0, '|', 260); set(0, '}', 334); set(0, '~', 584);
    return w;
})();

// Bold is a touch wider; a flat factor tracks it closely enough for layout.
function textWidth(text, size, bold = false) {
    const s = winAnsi(text);
    let total = 0;
    for (let i = 0; i < s.length; i++) total += HELVETICA_WIDTHS[s.charCodeAt(i)] ?? 556;
    return (total / 1000) * size * (bold ? 1.055 : 1);
}

function wrapText(text, size, maxWidth, bold = false) {
    const words = winAnsi(text).split(/\s+/).filter(Boolean);
    const lines = [];
    let line = '';

    for (const word of words) {
        const candidate = line ? line + ' ' + word : word;
        if (textWidth(candidate, size, bold) > maxWidth && line) {
            lines.push(line);
            line = word;
        } else {
            line = candidate;
        }
    }
    if (line) lines.push(line);
    return lines;
}

/** Builds the page content stream, one operator at a time. */
class Content {
    constructor() {
        this.ops = [];
    }

    fill([r, g, b]) {
        this.ops.push(`${r.toFixed(3)} ${g.toFixed(3)} ${b.toFixed(3)} rg`);
        return this;
    }

    stroke([r, g, b]) {
        this.ops.push(`${r.toFixed(3)} ${g.toFixed(3)} ${b.toFixed(3)} RG`);
        return this;
    }

    rect(x, y, w, h, color) {
        this.fill(color);
        this.ops.push(`${x.toFixed(2)} ${y.toFixed(2)} ${w.toFixed(2)} ${h.toFixed(2)} re f`);
        return this;
    }

    line(x1, y1, x2, y2, color, width = 0.75) {
        this.stroke(color);
        this.ops.push(`${width} w ${x1.toFixed(2)} ${y1.toFixed(2)} m ${x2.toFixed(2)} ${y2.toFixed(2)} l S`);
        return this;
    }

    text(x, y, string, { size = 10, bold = false, color = COLOR.ink, align = 'left', width = 0 } = {}) {
        let drawX = x;
        if (align === 'right') drawX = x - textWidth(string, size, bold);
        if (align === 'center') drawX = x + (width - textWidth(string, size, bold)) / 2;

        this.fill(color);
        this.ops.push(
            `BT /${bold ? 'F2' : 'F1'} ${size} Tf ${drawX.toFixed(2)} ${y.toFixed(2)} Td (${pdfString(string)}) Tj ET`
        );
        return this;
    }

    image(name, x, y, w, h) {
        this.ops.push(`q ${w.toFixed(2)} 0 0 ${h.toFixed(2)} ${x.toFixed(2)} ${y.toFixed(2)} cm /${name} Do Q`);
        return this;
    }

    toString() {
        return this.ops.join('\n');
    }
}

/**
 * Loads the site logo and hands back raw JPEG bytes plus its dimensions.
 *
 * The source is a WebP with transparency, which PDF cannot embed directly, so
 * it goes through a canvas and back out as JPEG. The canvas is flooded with
 * the header's own coral first rather than white: JPEG has no alpha, and on
 * white the mark arrives as a pale square stamped on the coral band.
 *
 * Returns null on any failure. A report without a logo is still a usable
 * report, and a broken one is not.
 */
async function loadLogo(url) {
    if (!url) return null;

    try {
        const image = await new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = url;
        });

        const size = 192;
        const canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;

        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#F47C6B';
        ctx.fillRect(0, 0, size, size);
        ctx.drawImage(image, 0, 0, size, size);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
        const binary = atob(dataUrl.split(',')[1]);

        return { data: binary, width: size, height: size };
    } catch {
        return null;
    }
}

/** Assembles the objects, cross-reference table and trailer into a PDF file. */
function buildPdf(contentStream, logo) {
    const objects = [];
    const add = (body) => {
        objects.push(body);
        return objects.length; // 1-based object number
    };

    const catalog = add('<< /Type /Catalog /Pages 2 0 R >>');
    const pages = add('<< /Type /Pages /Kids [3 0 R] /Count 1 >>');

    const xobject = logo ? '/XObject << /Im1 7 0 R >> ' : '';
    add(
        `<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ${PAGE.w} ${PAGE.h}] ` +
        `/Resources << /Font << /F1 4 0 R /F2 5 0 R >> ${xobject}>> /Contents 6 0 R >>`
    );

    add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');
    add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>');
    add(`<< /Length ${contentStream.length} >>\nstream\n${contentStream}\nendstream`);

    if (logo) {
        add(
            `<< /Type /XObject /Subtype /Image /Width ${logo.width} /Height ${logo.height} ` +
            `/ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ${logo.data.length} >>\n` +
            `stream\n${logo.data}\nendstream`
        );
    }

    let file = '%PDF-1.4\n';
    const offsets = [];

    objects.forEach((body, i) => {
        offsets.push(file.length);
        file += `${i + 1} 0 obj\n${body}\nendobj\n`;
    });

    const xrefStart = file.length;
    file += `xref\n0 ${objects.length + 1}\n0000000000 65535 f \n`;
    for (const offset of offsets) {
        file += String(offset).padStart(10, '0') + ' 00000 n \n';
    }
    file += `trailer\n<< /Size ${objects.length + 1} /Root ${catalog} 0 R >>\nstartxref\n${xrefStart}\n%%EOF`;

    // Every byte in `file` is already 0-255, binary JPEG included, so a
    // straight char-code copy preserves the image bytes intact.
    const bytes = new Uint8Array(file.length);
    for (let i = 0; i < file.length; i++) bytes[i] = file.charCodeAt(i) & 0xff;

    return new Blob([bytes], { type: 'application/pdf' });
}

/**
 * Lays out the report.
 *
 * `result` is the object the calculator stashes after a run: every number it
 * showed on screen, plus the multipliers behind them, so the PDF can show the
 * working rather than just repeating the answer.
 */
function layout(result, logo) {
    const c = new Content();
    const printed = new Date().toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric',
    });

    /* ── Header band ─────────────────────────────────────────────────── */
    const headerH = 76;
    c.rect(0, PAGE.h - headerH, PAGE.w, headerH, COLOR.coral);

    if (logo) {
        // White disc behind the mark, so the coral band does not swallow it.
        c.image('Im1', MARGIN, PAGE.h - headerH + 20, 36, 36);
    }

    const wordmarkX = MARGIN + (logo ? 46 : 0);
    c.text(wordmarkX, PAGE.h - headerH + 42, 'PurrQuery', { size: 19, bold: true, color: COLOR.white });
    c.text(wordmarkX, PAGE.h - headerH + 26, 'Free cat care tools and guides', { size: 8.5, color: COLOR.white });

    c.text(PAGE.w - MARGIN, PAGE.h - headerH + 42, 'CAT CALORIE REPORT', {
        size: 9.5, bold: true, color: COLOR.white, align: 'right',
    });
    c.text(PAGE.w - MARGIN, PAGE.h - headerH + 26, printed, {
        size: 8.5, color: COLOR.white, align: 'right',
    });

    let y = PAGE.h - headerH - 34;

    /* ── Title ───────────────────────────────────────────────────────── */
    // A 40-character cat name is allowed by the form, and at 20pt that runs
    // off the page. The size steps down until it fits rather than wrapping,
    // so the block below it stays where the rest of the layout expects.
    let heading = result.name ? `${result.name}'s daily calorie plan` : 'Daily calorie plan';
    let headingSize = 20;
    while (headingSize > 13 && textWidth(heading, headingSize, true) > CONTENT_W) headingSize -= 1;
    while (textWidth(heading, headingSize, true) > CONTENT_W && heading.length > 20) {
        heading = heading.slice(0, -1);
    }

    c.text(MARGIN, y, heading, { size: headingSize, bold: true, color: COLOR.ink });
    y -= 15;
    c.text(MARGIN, y, `${result.stageLabel}  |  ${result.weightLabel}  |  ${result.bcsLabel} body condition`, {
        size: 9.5, color: COLOR.inkMuted,
    });

    /* ── Headline figure ─────────────────────────────────────────────── */
    y -= 22;
    const cardH = 86;
    c.rect(MARGIN, y - cardH, CONTENT_W, cardH, COLOR.coralLight);
    c.rect(MARGIN, y - cardH, 4, cardH, COLOR.coral);

    c.text(MARGIN + 20, y - 26, 'DAILY CALORIE NEEDS', { size: 8.5, bold: true, color: COLOR.inkMuted });
    c.text(MARGIN + 20, y - 58, `${result.daily}`, { size: 34, bold: true, color: COLOR.ink });
    const dailyW = textWidth(`${result.daily}`, 34, true);
    c.text(MARGIN + 26 + dailyW, y - 58, 'kcal / day', { size: 12, bold: true, color: COLOR.inkMuted });
    c.text(MARGIN + 20, y - 74, `Healthy range: ${result.rangeLow} to ${result.rangeHigh} kcal/day`, {
        size: 9.5, color: COLOR.inkMuted,
    });

    // Resting energy, parked on the right of the same card.
    c.text(PAGE.w - MARGIN - 20, y - 26, 'RESTING ENERGY (RER)', {
        size: 8.5, bold: true, color: COLOR.inkMuted, align: 'right',
    });
    c.text(PAGE.w - MARGIN - 20, y - 50, `${result.rer} kcal/day`, {
        size: 15, bold: true, color: COLOR.ink, align: 'right',
    });
    c.text(PAGE.w - MARGIN - 20, y - 66, 'What your cat burns at rest', {
        size: 8.5, color: COLOR.inkMuted, align: 'right',
    });

    y -= cardH + 24;

    /* ── Section helper ──────────────────────────────────────────────── */
    const section = (title) => {
        c.text(MARGIN, y, title.toUpperCase(), { size: 9, bold: true, color: COLOR.ink });
        y -= 6;
        c.line(MARGIN, y, PAGE.w - MARGIN, y, COLOR.coral, 1.2);
        y -= 15;
    };

    /**
     * A label on the left, its value right-aligned opposite.
     *
     * The value wraps rather than running under the label: a free-fed feeding
     * note is a full sentence, and drawn as one right-aligned line it printed
     * straight through "Per meal".
     */
    const row = (label, value) => {
        c.text(MARGIN, y, label, { size: 9.5, color: COLOR.inkMuted });

        const available = CONTENT_W - textWidth(label, 9.5) - 16;
        const lines = wrapText(value, 9.5, available, true);

        let lineY = y;
        for (const line of lines) {
            c.text(PAGE.w - MARGIN, lineY, line, { size: 9.5, bold: true, color: COLOR.ink, align: 'right' });
            lineY -= 12;
        }

        y -= 8 + (lines.length - 1) * 12;
        c.line(MARGIN, y, PAGE.w - MARGIN, y, COLOR.line, 0.5);
        y -= 11;
    };

    /* ── Your cat ────────────────────────────────────────────────────── */
    section("Your cat's details");
    row('Name', result.name || 'Not given');
    row('Weight', result.weightLabel);
    row('Life stage', result.stageLabel);
    row('Spay / neuter status', result.neuterLabel);
    row('Activity level', result.activityLabel);
    row('Body condition score', `${result.bcsScore} of 5, ${result.bcsLabel}`);
    row('Living situation', result.livingLabel);

    /* ── The working ─────────────────────────────────────────────────── */
    y -= 10;
    section('How this number was worked out');
    row('Resting energy requirement', `70 x ${result.weightKg} kg ^ 0.75 = ${result.rer} kcal`);
    row('Life stage multiplier', `x ${result.stageMultiplier}`);
    row('Activity adjustment', `x ${result.activityMultiplier}`);
    row('Living situation adjustment', `x ${result.livingMultiplier}`);
    row('Body condition adjustment', result.bcsAdjustment);

    c.text(MARGIN, y + 2, 'Daily calorie target', { size: 10.5, bold: true, color: COLOR.ink });
    c.text(PAGE.w - MARGIN, y + 2, `${result.daily} kcal/day`, {
        size: 10.5, bold: true, color: COLOR.coral, align: 'right',
    });
    y -= 24;

    /* ── Feeding plan ────────────────────────────────────────────────── */
    section('Feeding plan');
    row('Food type', result.foodLabel);
    row('Daily portion', result.foodPortion);
    row('Feeding frequency', result.frequencyLabel);
    row('Per meal', result.mealPortion);

    /* ── Body condition note ─────────────────────────────────────────── */
    y -= 8;
    const ok = result.bcsScore === 3;
    const noteLines = wrapText(result.bcsMessage, 9.5, CONTENT_W - 36);
    const noteH = 22 + noteLines.length * 13;

    c.rect(MARGIN, y - noteH, CONTENT_W, noteH, ok ? COLOR.sageLight : COLOR.amberLight);
    c.rect(MARGIN, y - noteH, 4, noteH, ok ? COLOR.sage : COLOR.amber);

    let noteY = y - 20;
    for (const line of noteLines) {
        c.text(MARGIN + 20, noteY, line, { size: 9.5, bold: true, color: ok ? COLOR.sage : COLOR.amber });
        noteY -= 13;
    }
    y -= noteH + 18;

    /* ── Caveats ─────────────────────────────────────────────────────── */
    const caveat =
        'This estimate is a starting point, not a prescription. Adjust it by about 10% either way as your ' +
        'cat\'s weight and body condition change, and check the calorie statement on your own packaging, ' +
        'since brands vary widely. Formula based on National Research Council (NRC) guidelines and AAFCO ' +
        'standards. General information, not veterinary advice.';

    for (const line of wrapText(caveat, 8.5, CONTENT_W)) {
        c.text(MARGIN, y, line, { size: 8.5, color: COLOR.inkMuted });
        y -= 11;
    }

    /* ── Footer ──────────────────────────────────────────────────────── */
    const footerY = 46;
    c.line(MARGIN, footerY + 18, PAGE.w - MARGIN, footerY + 18, COLOR.line, 0.75);
    c.text(MARGIN, footerY + 4, 'purrquery.com/tools/cat-calorie-calculator', {
        size: 8.5, bold: true, color: COLOR.coral,
    });
    c.text(PAGE.w / 2 - 60, footerY + 4, `Printed ${printed}`, {
        size: 8.5, color: COLOR.inkMuted, align: 'center', width: 120,
    });
    c.text(PAGE.w - MARGIN, footerY + 4, 'Page 1 of 1', {
        size: 8.5, color: COLOR.inkMuted, align: 'right',
    });

    return c.toString();
}

/** Builds and downloads the report. Returns false if there is nothing to print. */
export async function downloadCalorieReport(result, logoUrl) {
    if (!result) return false;

    const logo = await loadLogo(logoUrl);
    const blob = buildPdf(layout(result, logo), logo);

    const slug = (result.name || 'cat').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `purrquery-calorie-report-${slug || 'cat'}.pdf`;

    document.body.append(link);
    link.click();
    link.remove();

    // Revoked on the next tick rather than immediately: Safari has been known
    // to cancel an in-flight download when the URL disappears too fast.
    setTimeout(() => URL.revokeObjectURL(link.href), 10000);

    return true;
}
