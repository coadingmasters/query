# Images

Drop original image files in this folder. **These are sources, not what the
site serves.** A build step reads them and writes optimised, correctly sized
copies into `public/images/`, which is what the pages actually load.

That split exists so originals stay untouched and re-exportable: if the
compression settings or the required sizes change later, the sources are
still here to regenerate from.

## Where different kinds of images go

| Kind | Folder | Notes |
|---|---|---|
| Brand and site art — logo, illustrations, icons, page graphics | `resources/images/` | Committed to git. Ships with the code. |
| Fixed-URL files — `favicon.ico`, `og-image.png` | `public/` | The URL must stay predictable, so these are not renamed or hashed. |
| Uploaded content — blog post images, tool screenshots | `storage/app/public/` | Served via the `public/storage` symlink. Never committed, and deploys leave it alone. |

## What to provide

- **Largest version you have.** Downscaling is lossless in quality terms;
  upscaling is not. A 3000px-wide original is fine and preferred.
- **Photographs:** JPEG or PNG. WebP and AVIF are generated automatically —
  no need to convert anything by hand.
- **Logos, icons, diagrams:** SVG if it exists. SVG stays razor sharp at every
  screen density and is usually the smallest file. Only fall back to PNG when
  there is no vector version.
- **Screenshots:** PNG, captured at 2× if possible.

## Naming

Lowercase, hyphen-separated, describing the subject rather than its use:

    pdf-merge-tool.png        good
    hero-image-final-v2.png   avoid

The filename becomes part of the URL, so it is a small SEO signal. It also
seeds the `alt` text, which is both an accessibility requirement and how
search engines read the image.

Use subfolders once there are enough files to warrant it:

    resources/images/tools/pdf-merge.png
    resources/images/blog/2026-08-choosing-a-password-manager.jpg

## What happens to them

Each source produces AVIF, WebP and an original-format fallback, at several
widths. Pages get a `<picture>` element with a `srcset`, so a phone downloads
a phone-sized file and a desktop downloads a desktop-sized one.

Every generated tag carries explicit `width` and `height`. That is what keeps
Cumulative Layout Shift at zero: the browser reserves the right amount of
space before the image arrives, so text never jumps as the page loads.
