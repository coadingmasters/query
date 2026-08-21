/**
 * A one page, branded PDF report for the cat vaccination tracker.
 *
 * Written against the PDF format directly, the same way the calorie
 * calculator's report is: the page promises the tracker runs entirely in the
 * browser and sends nothing anywhere, which a server-rendered PDF would
 * quietly break, and a hand-rolled writer is smaller than the smallest
 * client-side PDF library.
 *
 * The vaccine list this tracker can produce is bounded (core vaccines plus
 * at most four lifestyle ones, each with a handful of doses), so unlike a
 * document of arbitrary length this fits one page by construction rather
 * than needing real pagination.
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
    danger: [0.773, 0.129, 0.125],
    dangerLight: [0.992, 0.925, 0.925],
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
    const set = (from, chars, width) => { for (const c of chars) w[c.charCodeAt(0)] = width; };
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

/** Truncates to a max width with an ellipsis, for a table cell that must stay on one line. */
function fitText(text, size, maxWidth, bold = false) {
    const s = winAnsi(text);
    if (textWidth(s, size, bold) <= maxWidth) return s;
    let cut = s;
    while (cut.length > 1 && textWidth(cut + '...', size, bold) > maxWidth) cut = cut.slice(0, -1);
    return cut + '...';
}

class Content {
    constructor() { this.ops = []; }

    fill([r, g, b]) { this.ops.push(`${r.toFixed(3)} ${g.toFixed(3)} ${b.toFixed(3)} rg`); return this; }
    stroke([r, g, b]) { this.ops.push(`${r.toFixed(3)} ${g.toFixed(3)} ${b.toFixed(3)} RG`); return this; }

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
        this.ops.push(`BT /${bold ? 'F2' : 'F1'} ${size} Tf ${drawX.toFixed(2)} ${y.toFixed(2)} Td (${pdfString(string)}) Tj ET`);
        return this;
    }

    image(name, x, y, w, h) {
        this.ops.push(`q ${w.toFixed(2)} 0 0 ${h.toFixed(2)} ${x.toFixed(2)} ${y.toFixed(2)} cm /${name} Do Q`);
        return this;
    }

    toString() { return this.ops.join('\n'); }
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
    const add = (body) => { objects.push(body); return objects.length; };

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
    for (const offset of offsets) file += String(offset).padStart(10, '0') + ' 00000 n \n';
    file += `trailer\n<< /Size ${objects.length + 1} /Root ${catalog} 0 R >>\nstartxref\n${xrefStart}\n%%EOF`;

    const bytes = new Uint8Array(file.length);
    for (let i = 0; i < file.length; i++) bytes[i] = file.charCodeAt(i) & 0xff;
    return new Blob([bytes], { type: 'application/pdf' });
}

/**
 * `report` mirrors what the results panel already computed: the row list
 * (vaccine, status, dates), the three status-summary counts, the cat's name
 * and date of birth, and the injection-site reference the page shows.
 */
function layout(report, logo) {
    const c = new Content();
    const printed = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

    /* ── Header band ─────────────────────────────────────────────────── */
    const headerH = 76;
    c.rect(0, PAGE.h - headerH, PAGE.w, headerH, COLOR.coral);
    if (logo) c.image('Im1', MARGIN, PAGE.h - headerH + 20, 36, 36);

    const wordmarkX = MARGIN + (logo ? 46 : 0);
    c.text(wordmarkX, PAGE.h - headerH + 42, 'PurrQuery', { size: 19, bold: true, color: COLOR.white });
    c.text(wordmarkX, PAGE.h - headerH + 26, 'Free cat care tools and guides', { size: 8.5, color: COLOR.white });

    c.text(PAGE.w - MARGIN, PAGE.h - headerH + 42, 'VACCINATION RECORD', {
        size: 9.5, bold: true, color: COLOR.white, align: 'right',
    });
    c.text(PAGE.w - MARGIN, PAGE.h - headerH + 26, printed, {
        size: 8.5, color: COLOR.white, align: 'right',
    });

    let y = PAGE.h - headerH - 34;

    /* ── Title ───────────────────────────────────────────────────────── */
    let heading = `${report.catLabel}'s vaccination record`;
    let headingSize = 20;
    while (headingSize > 13 && textWidth(heading, headingSize, true) > CONTENT_W) headingSize -= 1;
    while (textWidth(heading, headingSize, true) > CONTENT_W && heading.length > 20) heading = heading.slice(0, -1);
    c.text(MARGIN, y, heading, { size: headingSize, bold: true, color: COLOR.ink });
    y -= 15;
    c.text(MARGIN, y, `${report.dobLabel}  |  Based on AAFP 2020 and WSAVA 2024 guidelines`, {
        size: 9.5, color: COLOR.inkMuted,
    });

    /* ── Status summary ──────────────────────────────────────────────── */
    y -= 26;
    const chipW = (CONTENT_W - 16) / 3;
    const chips = [
        { n: report.upToDate, label: 'Up to date', bg: COLOR.sageLight, fg: COLOR.sage },
        { n: report.dueSoon, label: 'Due soon', bg: COLOR.amberLight, fg: COLOR.amber },
        { n: report.overdue, label: 'Overdue / not started', bg: COLOR.dangerLight, fg: COLOR.danger },
    ];
    chips.forEach((chip, i) => {
        const x = MARGIN + i * (chipW + 8);
        c.rect(x, y - 40, chipW, 40, chip.bg);
        c.text(x + chipW / 2, y - 16, String(chip.n), { size: 16, bold: true, color: chip.fg, align: 'center', width: 0 });
        c.text(x, y - 32, chip.label, { size: 7.5, bold: true, color: chip.fg, align: 'center', width: chipW });
    });
    y -= 58;

    /* ── Table ───────────────────────────────────────────────────────── */
    const cols = [
        { key: 'vaccine', label: 'Vaccine', w: 0.34 },
        { key: 'status', label: 'Status', w: 0.16 },
        { key: 'given', label: 'Date given', w: 0.18 },
        { key: 'due', label: 'Next due', w: 0.18 },
        { key: 'days', label: 'Days', w: 0.14 },
    ];
    let cx = MARGIN;
    const colX = cols.map(col => { const x = cx; cx += col.w * CONTENT_W; return x; });

    c.rect(MARGIN, y - 20, CONTENT_W, 20, COLOR.coralLight);
    cols.forEach((col, i) => c.text(colX[i] + 6, y - 14, col.label.toUpperCase(), { size: 7.5, bold: true, color: COLOR.ink }));
    y -= 20;

    const TONE_BG = { green: COLOR.sageLight, yellow: COLOR.amberLight, red: COLOR.dangerLight, gray: [0.97, 0.965, 0.957] };
    const TONE_FG = { green: COLOR.sage, yellow: COLOR.amber, red: COLOR.danger, gray: COLOR.inkMuted };

    const rowH = 18;
    report.rows.forEach((row, i) => {
        const rowY = y - (i + 1) * rowH;
        if (i % 2 === 1) c.rect(MARGIN, rowY, CONTENT_W, rowH, [0.988, 0.984, 0.976]);

        c.text(colX[0] + 6, rowY + 6, fitText(row.dose, 8.5, cols[0].w * CONTENT_W - 10), { size: 8.5, color: COLOR.ink });

        const badgeW = textWidth(row.statusLabel, 7.5, true) + 12;
        c.rect(colX[1] + 6, rowY + 4, badgeW, 11, TONE_BG[row.tone]);
        c.text(colX[1] + 12, rowY + 6.5, row.statusLabel, { size: 7.5, bold: true, color: TONE_FG[row.tone] });

        c.text(colX[2] + 6, rowY + 6, row.given, { size: 8.5, color: COLOR.inkMuted });
        c.text(colX[3] + 6, rowY + 6, row.due, { size: 8.5, color: COLOR.inkMuted });
        c.text(colX[4] + 6, rowY + 6, row.days, { size: 8.5, color: COLOR.inkMuted });
    });
    y -= report.rows.length * rowH;
    c.line(MARGIN, y, PAGE.w - MARGIN, y, COLOR.line, 0.75);
    y -= 20;

    /* ── Injection sites ─────────────────────────────────────────────── */
    const siteLines = report.injectionSites.map(s => `${s.vaccine}: ${s.site}`);
    const ruleLines = wrapText(
        'Record the site and lot number at every visit. A lump present after 3 months, 2cm or larger, or still growing at 3 months: contact your vet (the 3-2-3 rule).',
        8.5, CONTENT_W - 36
    );
    const boxH = 34 + siteLines.length * 12 + ruleLines.length * 11;
    c.rect(MARGIN, y - boxH, CONTENT_W, boxH, COLOR.coralLight);
    c.rect(MARGIN, y - boxH, 4, boxH, COLOR.coral);
    c.text(MARGIN + 20, y - 18, 'AAFP INJECTION SITE GUIDELINES', { size: 8.5, bold: true, color: COLOR.ink });
    let siteY = y - 32;
    siteLines.forEach(line => { c.text(MARGIN + 20, siteY, line, { size: 8.5, color: COLOR.ink }); siteY -= 12; });
    siteY -= 4;
    ruleLines.forEach(line => { c.text(MARGIN + 20, siteY, line, { size: 8, color: COLOR.inkMuted }); siteY -= 11; });
    y -= boxH + 18;

    /* ── Fill-in fields for the vet ──────────────────────────────────── */
    c.text(MARGIN, y, 'Lot #: ______________________', { size: 9, color: COLOR.inkMuted });
    c.text(MARGIN + 190, y, 'Vet / clinic: ______________________', { size: 9, color: COLOR.inkMuted });
    y -= 24;

    /* ── Caveat ──────────────────────────────────────────────────────── */
    const caveat =
        'This schedule is for reference only. Always confirm with your veterinarian: local laws, especially for rabies, ' +
        'vary by state and country, and your vet can account for your cat\'s actual health and history.';
    for (const line of wrapText(caveat, 8.5, CONTENT_W)) {
        c.text(MARGIN, y, line, { size: 8.5, color: COLOR.inkMuted });
        y -= 11;
    }

    /* ── Footer ──────────────────────────────────────────────────────── */
    const footerY = 46;
    c.line(MARGIN, footerY + 18, PAGE.w - MARGIN, footerY + 18, COLOR.line, 0.75);
    c.text(MARGIN, footerY + 4, 'purrquery.com/tools/cat-vaccination-tracker', { size: 8.5, bold: true, color: COLOR.coral });
    c.text(PAGE.w / 2 - 60, footerY + 4, `Printed ${printed}`, { size: 8.5, color: COLOR.inkMuted, align: 'center', width: 120 });
    c.text(PAGE.w - MARGIN, footerY + 4, 'Page 1 of 1', { size: 8.5, color: COLOR.inkMuted, align: 'right' });

    return c.toString();
}

/** Builds and downloads the report. Returns false if there is nothing to print. */
export async function downloadVaccinationReport(report, logoUrl) {
    if (!report) return false;

    const logo = await loadLogo(logoUrl);
    const blob = buildPdf(layout(report, logo), logo);

    const slug = (report.catLabel || 'cat').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `purrquery-vaccination-record-${slug || 'cat'}.pdf`;

    document.body.append(link);
    link.click();
    link.remove();
    setTimeout(() => URL.revokeObjectURL(link.href), 10000);

    return true;
}
