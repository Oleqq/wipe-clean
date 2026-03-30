import { mkdir, readFile, writeFile } from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import esbuild from "esbuild";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const themeRoot = path.resolve(__dirname, "..");

const cssEntry = path.join(themeRoot, "static", "css", "style.css");
const cssOutfile = path.join(themeRoot, "static", "css", "style.min.css");
const jsEntry = path.join(themeRoot, "static", "js", "script.js");
const jsOutfile = path.join(themeRoot, "static", "js", "script.min.js");

const isWatch = process.argv.includes("--watch");

async function ensureDir(filePath) {
  await mkdir(path.dirname(filePath), { recursive: true });
}

async function buildCss() {
  await ensureDir(cssOutfile);

  const source = await readFile(cssEntry, "utf8");
  const result = await esbuild.transform(source, {
    loader: "css",
    minify: true,
    legalComments: "none"
  });

  await writeFile(cssOutfile, result.code, "utf8");
}

async function buildJs() {
  await ensureDir(jsOutfile);

  await esbuild.build({
    entryPoints: [jsEntry],
    outfile: jsOutfile,
    bundle: true,
    format: "esm",
    target: ["es2019"],
    minify: true,
    sourcemap: false,
    legalComments: "none",
    logLevel: "info"
  });
}

async function runBuild() {
  await buildCss();
  await buildJs();
}

if (isWatch) {
  await buildCss();

  const cssContext = await esbuild.context({
    entryPoints: [cssEntry],
    bundle: false,
    loader: {
      ".css": "css"
    },
    minify: true,
    outfile: cssOutfile,
    legalComments: "none",
    logLevel: "info"
  });

  const jsContext = await esbuild.context({
    entryPoints: [jsEntry],
    bundle: true,
    format: "esm",
    target: ["es2019"],
    minify: true,
    outfile: jsOutfile,
    legalComments: "none",
    logLevel: "info"
  });

  await Promise.all([cssContext.watch(), jsContext.watch()]);
  console.log("Watching theme assets...");
} else {
  await runBuild();
}
