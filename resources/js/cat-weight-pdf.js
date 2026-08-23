/**
 * A one page, branded PDF report for the cat weight checker.
 *
 * Written against the PDF format directly rather than pulled from a library,
 * matching cat-calorie-pdf.js: the page promises everything runs in the
 * browser and sends nothing anywhere, which a server-rendered PDF would
 * quietly break, and the smallest client-side PDF library is larger than
 * this whole page's JavaScript budget for a feature most visitors never
 * touch.
 *
 * PDF puts its origin at the bottom left and measures in points, 72 to the
 * inch. Every y below is therefore a distance up from the bottom of an A4
 * page, which is why the layout is written top down with a cursor that
 * decreases.
 */

const PAGE = { w: 595.28, h: 841.89 };
const MARGIN = 48;
const CONTENT_W = PAGE.w - MARGIN * 2;

const COLOR = {
    coral: [0.957, 0.486, 0.42],
    coralLight: [0.988, 0.891, 0.867],
    ink: [0.071, 0.22, 0.231],
    inkMuted: [0.322, 0.396, 0.408],
    white: [1, 1, 1],
    line: [0.929, 0.906, 0.882],
    sage: [0.31, 0.424, 0.286],
    sageLight: [0.91, 0.941, 0.894],
    amber: [0.576, 0.361, 0.043],
    amberLight: [0.992, 0.953, 0.886],
};

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

function pdfString(text) {
    return winAnsi(text).replace(/([\\()])/g, '\\$1');
}

const HELVETICA_WIDTHS = (() => {
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

function buildPdf(contentStream, logo) {
    const objects = [];
    const add = (body) => {
        objects.push(body);
        return objects.length;
    };

    const catalog = add('<< /Type /Catalog /Pages 2 0 R >>');
    add('<< /Type /Pages /Kids [3 0 R] /Count 1 >>');

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

    const bytes = new Uint8Array(file.length);
    for (let i = 0; i < file.length; i++) bytes[i] = file.charCodeAt(i) & 0xff;

    return new Blob([bytes], { type: 'application/pdf' });
}

/**
 * Lays out the report. `result` is what the page's JS builds after a check:
 * weight, BCS, the estimated ideal range, the food adjustment if any, and
 * the weight log if the visitor has been keeping one.
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
        c.image('Im1', MARGIN, PAGE.h - headerH + 20, 36, 36);
    }

    const wordmarkX = MARGIN + (logo ? 46 : 0);
    c.text(wordmarkX, PAGE.h - headerH + 42, 'PurrQuery', { size: 19, bold: true, color: COLOR.white });
    c.text(wordmarkX, PAGE.h - headerH + 26, 'Free cat care tools and guides', { size: 8.5, color: COLOR.white });

    c.text(PAGE.w - MARGIN, PAGE.h - headerH + 42, 'CAT WEIGHT REPORT', {
        size: 9.5, bold: true, color: COLOR.white, align: 'right',
    });
    c.text(PAGE.w - MARGIN, PAGE.h - headerH + 26, printed, {
        size: 8.5, color: COLOR.white, align: 'right',
    });

    let y = PAGE.h - headerH - 34;

    /* ── Title ───────────────────────────────────────────────────────── */
    let heading = `${result.name}'s weight report`;
    let headingSize = 20;
    while (headingSize > 13 && textWidth(heading, headingSize, true) > CONTENT_W) headingSize -= 1;
    while (textWidth(heading, headingSize, true) > CONTENT_W && heading.length > 20) {
        heading = heading.slice(0, -1);
    }

    c.text(MARGIN, y, heading, { size: headingSize, bold: true, color: COLOR.ink });
    y -= 15;
    const ageNote = result.age != null ? `${result.age} years old${result.isSenior ? ' (senior)' : ''}` : null;
    c.text(MARGIN, y, [`${result.weightLb.toFixed(1)} lb`, `${result.bcsLabel} body condition`, ageNote].filter(Boolean).join('  |  '), {
        size: 9.5, color: COLOR.inkMuted,
    });

    /* ── Headline card ───────────────────────────────────────────────── */
    y -= 22;
    const cardH = 86;
    c.rect(MARGIN, y - cardH, CONTENT_W, cardH, COLOR.coralLight);
    c.rect(MARGIN, y - cardH, 4, cardH, COLOR.coral);

    c.text(MARGIN + 20, y - 26, 'CURRENT WEIGHT', { size: 8.5, bold: true, color: COLOR.inkMuted });
    const weightStr = result.unit === 'kg' ? (result.weightLb * 0.45359237).toFixed(1) : result.weightLb.toFixed(1);
    c.text(MARGIN + 20, y - 58, weightStr, { size: 34, bold: true, color: COLOR.ink });
    const wW = textWidth(weightStr, 34, true);
    c.text(MARGIN + 26 + wW, y - 58, result.unit, { size: 12, bold: true, color: COLOR.inkMuted });
    c.text(MARGIN + 20, y - 74, `BCS ${result.bcsScore} of 5`, { size: 9.5, color: COLOR.inkMuted });

    c.text(PAGE.w - MARGIN - 20, y - 26, 'ESTIMATED IDEAL WEIGHT', {
        size: 8.5, bold: true, color: COLOR.inkMuted, align: 'right',
    });
    c.text(PAGE.w - MARGIN - 20, y - 50, `${result.idealLow.toFixed(1)}-${result.idealHigh.toFixed(1)} ${result.unit}`, {
        size: 15, bold: true, color: COLOR.ink, align: 'right',
    });
    c.text(PAGE.w - MARGIN - 20, y - 66, 'An estimate, not a diagnosis', {
        size: 8.5, color: COLOR.inkMuted, align: 'right',
    });

    y -= cardH + 24;

    const section = (title) => {
        c.text(MARGIN, y, title.toUpperCase(), { size: 9, bold: true, color: COLOR.ink });
        y -= 6;
        c.line(MARGIN, y, PAGE.w - MARGIN, y, COLOR.coral, 1.2);
        y -= 15;
    };

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

    /* ── Body condition ──────────────────────────────────────────────── */
    section('Body condition');
    row('Score', `${result.bcsScore} of 5, ${result.bcsLabel}`);
    row('Estimated ideal weight', `${result.idealLow.toFixed(1)}-${result.idealHigh.toFixed(1)} ${result.unit}`);
    if (ageNote) row('Age', ageNote);

    /* ── Food adjustment ─────────────────────────────────────────────── */
    y -= 10;
    section('Food adjustment');
    if (!result.adjustment) {
        row('Recommendation', 'None needed, at an ideal body condition');
    } else {
        const a = result.adjustment;
        row('Direction', a.overweight ? `Reduce by about ${a.percent}%` : `Increase by about ${a.percent}%`);
        row('Current maintenance (est.)', `${a.maintenance} kcal/day`);
        row('Adjusted target (est.)', `${a.adjusted} kcal/day`);
    }

    /* ── Note ─────────────────────────────────────────────────────────── */
    y -= 8;
    const ok = result.bcsScore === 3;
    const noteText = ok
        ? `${result.name} is at an ideal body condition. Keep feeding what's working, and re-check every few weeks.`
        : (result.adjustment && result.adjustment.overweight
            ? 'Cats that lose weight too quickly risk hepatic lipidosis, a serious liver condition. Lose no more than 1-1.5% of body weight per week.'
            : (result.isSenior
                ? 'Unexplained weight change in a senior cat is worth a vet visit before a diet change alone.'
                : "If weight doesn't move within a few weeks, a vet check is worth ruling out an underlying cause."));
    const noteLines = wrapText(noteText, 9.5, CONTENT_W - 36);
    const noteH = 22 + noteLines.length * 13;

    c.rect(MARGIN, y - noteH, CONTENT_W, noteH, ok ? COLOR.sageLight : COLOR.amberLight);
    c.rect(MARGIN, y - noteH, 4, noteH, ok ? COLOR.sage : COLOR.amber);

    let noteY = y - 20;
    for (const line of noteLines) {
        c.text(MARGIN + 20, noteY, line, { size: 9.5, bold: true, color: ok ? COLOR.sage : COLOR.amber });
        noteY -= 13;
    }
    y -= noteH + 18;

    /* ── Weight log ──────────────────────────────────────────────────── */
    const log = (result.log || []).slice(-8);
    if (log.length >= 2) {
        section('Recent weight log');
        for (const entry of log) {
            const d = new Date(entry.date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const w = result.unit === 'kg' ? (entry.weightLb * 0.45359237).toFixed(1) : entry.weightLb.toFixed(1);
            row(d, `${w} ${result.unit}`);
        }
        y -= 6;
    }

    /* ── Caveat ──────────────────────────────────────────────────────── */
    const caveat =
        'This report estimates ideal weight from current weight and body condition score together, using a ' +
        'widely cited veterinary approximation. It is a starting point, not a diagnosis, and a vet\'s hands-on ' +
        'exam gives the precise number. General information, not veterinary advice.';

    for (const line of wrapText(caveat, 8.5, CONTENT_W)) {
        c.text(MARGIN, y, line, { size: 8.5, color: COLOR.inkMuted });
        y -= 11;
    }

    /* ── Footer ──────────────────────────────────────────────────────── */
    const footerY = 46;
    c.line(MARGIN, footerY + 18, PAGE.w - MARGIN, footerY + 18, COLOR.line, 0.75);
    c.text(MARGIN, footerY + 4, 'purrquery.com/tools/cat-weight-checker', {
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
export async function downloadWeightReport(result, logoUrl) {
    if (!result) return false;

    const logo = await loadLogo(logoUrl);
    const blob = buildPdf(layout(result, logo), logo);

    const slug = (result.name || 'cat').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `purrquery-weight-report-${slug || 'cat'}.pdf`;

    document.body.append(link);
    link.click();
    link.remove();

    setTimeout(() => URL.revokeObjectURL(link.href), 10000);

    return true;
}
