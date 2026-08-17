#!/usr/bin/env python3
"""Render public/og-image.png, the social-share card for the launch page.

Committed as a script rather than a one-off so the card can be regenerated
whenever the brand copy or palette changes. Run: python3 scripts/make-og-image.py
"""
from PIL import Image, ImageDraw, ImageFilter, ImageFont

W, H = 1200, 630
SURFACE = (255, 255, 255)
SURFACE_SOFT = (248, 247, 255)
PRIMARY = (83, 74, 183)
ACCENT = (25, 133, 99)
ACCENT_VIVID = (29, 158, 117)
INK = (26, 26, 46)
INK_SOFT = (99, 107, 119)
LINE = (229, 227, 248)

FONT_DIR = "/usr/share/fonts/truetype/lato"
black = ImageFont.truetype(f"{FONT_DIR}/Lato-Black.ttf", 104)
regular = ImageFont.truetype(f"{FONT_DIR}/Lato-Regular.ttf", 40)
semibold = ImageFont.truetype(f"{FONT_DIR}/Lato-Semibold.ttf", 26)


def centred(draw, y, text, font, fill):
    """Draw `text` horizontally centred on the canvas, return its height."""
    left, top, right, bottom = draw.textbbox((0, 0), text, font=font)
    draw.text(((W - (right - left)) / 2 - left, y - top), text, font=font, fill=fill)
    return bottom - top


# --- background: soft tint plus two blurred colour fields -------------------
card = Image.new("RGB", (W, H), SURFACE_SOFT)
blobs = Image.new("RGB", (W, H), SURFACE_SOFT)
bd = ImageDraw.Draw(blobs)
bd.ellipse((-220, -260, 380, 340), fill=(214, 210, 245))
bd.ellipse((900, 380, 1460, 940), fill=(206, 236, 226))
card = Image.blend(card, blobs.filter(ImageFilter.GaussianBlur(120)), 0.9)

d = ImageDraw.Draw(card)

# --- logo mark: magnifier lens holding a paw --------------------------------
cx, cy, r = W // 2 - 8, 162, 56
d.ellipse((cx - r, cy - r, cx + r, cy + r), outline=PRIMARY, width=12)

# Handle. Pillow has no round line caps, so the ends are capped with circles.
hx1, hy1, hx2, hy2, hw = cx + 42, cy + 42, cx + 84, cy + 84, 16
d.line((hx1, hy1, hx2, hy2), fill=ACCENT, width=hw)
for ex, ey in ((hx1, hy1), (hx2, hy2)):
    d.ellipse((ex - hw / 2, ey - hw / 2, ex + hw / 2, ey + hw / 2), fill=ACCENT)

d.ellipse((cx - 17, cy + 6, cx + 17, cy + 32), fill=PRIMARY)           # pad
for tx, ty in ((-25, -14), (-10, -27), (10, -27), (25, -14)):          # toes
    d.ellipse((cx + tx - 7, cy + ty - 7, cx + tx + 7, cy + ty + 7), fill=PRIMARY)

# --- wordmark and copy ------------------------------------------------------
centred(d, 288, "PuurQuery", black, INK)
centred(d, 418, "Free online tools and practical guides", regular, INK_SOFT)

# --- "launching soon" pill --------------------------------------------------
label = "LAUNCHING SOON"
left, top, right, bottom = d.textbbox((0, 0), label, font=semibold)
tw, th = right - left, bottom - top
pw, ph = tw + 108, th + 40
px, py = (W - pw) / 2, 500
d.rounded_rectangle((px, py, px + pw, py + ph), radius=ph / 2, fill=SURFACE, outline=LINE, width=2)
dot = py + ph / 2
d.ellipse((px + 36, dot - 8, px + 52, dot + 8), fill=ACCENT_VIVID)
d.text((px + 70 - left, py + 20 - top), label, font=semibold, fill=PRIMARY)

card.save("public/og-image.png", optimize=True)
print(f"public/og-image.png written ({W}x{H})")
