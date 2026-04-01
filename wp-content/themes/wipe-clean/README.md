# wipe-clean theme

WordPress theme for the `wipe-clean` project.

## Build

From the theme directory:

```bash
npm install
npm run build
```

The build script updates:

- `static/js/script.min.js`
- `static/css/style.min.css`

## Notes

- Frontend forms use a neutral placeholder contour and are prepared for a later switch to Contact Form 7 shortcodes.
- Header and footer are rendered from the current theme shell, without the old static fallback layer.
- Archive settings for services are edited from the archive settings screen in admin.
