#!/usr/bin/env python3
"""Render public/og-image.png, the card every page shares to social and the
image named in the Organization schema's logo field.

Committed as a script rather than a one-off so the card can be regenerated
whenever the brand copy or palette changes. Run: python3 scripts/make-og-image.py

Rebuilt from a version that predated both the coral/teal palette and the real
logo: it drew its own unrelated magnifier-and-paw glyph in the old purple, and
still said "launching soon" on a site that has been live for weeks. Every
social share and every crawler reading the Organization schema was showing
that stale mark until this ran.
"""
from PIL import Image, ImageChops, ImageDraw, ImageFilter, ImageFont

W, H = 1200, 630
SURFACE_SOFT = (255, 241, 236)     # --color-surface-soft
PRIMARY_VIVID = (244, 124, 107)    # --color-primary-vivid, coral
ACCENT_LIGHT = (232, 240, 228)     # --color-accent-light, pale sage
INK = (18, 56, 59)                 # --color-ink, deep teal
INK_MUTED = (82, 101, 104)         # --color-ink-muted

FONT_DIR = "/usr/share/fonts/truetype/lato"
black = ImageFont.truetype(f"{FONT_DIR}/Lato-Black.ttf", 100)
regular = ImageFont.truetype(f"{FONT_DIR}/Lato-Regular.ttf", 38)


def centred(draw, y, text, font, fill):
    """Draw `text` horizontally centred on the canvas."""
    left, top, right, bottom = draw.textbbox((0, 0), text, font=font)
    draw.text(((W - (right - left)) / 2 - left, y - top), text, font=font, fill=fill)


# --- background: soft tint plus two blurred colour fields -------------------
card = Image.new("RGB", (W, H), SURFACE_SOFT)
blobs = Image.new("RGB", (W, H), SURFACE_SOFT)
bd = ImageDraw.Draw(blobs)
bd.ellipse((-240, -280, 360, 320), fill=PRIMARY_VIVID)
bd.ellipse((900, 380, 1480, 960), fill=ACCENT_LIGHT)
card = Image.blend(card, blobs.filter(ImageFilter.GaussianBlur(130)), 0.35)

d = ImageDraw.Draw(card)

# --- logo mark: the real icon, not a redrawn stand-in ------------------------
# icon-512.png will not do here: it is flattened onto an opaque white square
# by make-favicons.py, and pasting a square onto a tinted card draws a visible
# box around the circle. Cropped fresh from the source logo instead, with
# transparency preserved outside the coral disc the same way the favicon
# script does it.
CIRCLE_BOX = (300, 10, 668, 668)
source_logo = Image.open("resources/images/purrquery.webp").convert("RGBA")
icon = source_logo.crop((
    CIRCLE_BOX[0], CIRCLE_BOX[1],
    CIRCLE_BOX[0] + CIRCLE_BOX[2], CIRCLE_BOX[1] + CIRCLE_BOX[3],
))
ellipse = Image.new("L", icon.size, 0)
ImageDraw.Draw(ellipse).ellipse((0, 0, icon.width - 1, icon.height - 1), fill=255)
icon.putalpha(ImageChops.multiply(ellipse, icon.getchannel("A")))

logo_size = 168
icon = icon.resize((logo_size, logo_size), Image.LANCZOS)
card.paste(icon, ((W - logo_size) // 2, 92), icon)

# --- wordmark and copy -------------------------------------------------------
centred(d, 296, "PurrQuery", black, INK)
centred(d, 424, "Smart tools and clear answers for cat owners", regular, INK_MUTED)

card.save("public/og-image.png", optimize=True)
print(f"public/og-image.png written ({W}x{H})")
