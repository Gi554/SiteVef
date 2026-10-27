// Optional visual check: VEF_PLAYWRIGHT_MODULE may point to a local Playwright installation.
const { chromium } = require(process.env.VEF_PLAYWRIGHT_MODULE || 'playwright');
const path = require('node:path');
const { pathToFileURL } = require('node:url');

(async () => {
    const browser = await chromium.launch({ channel: 'msedge', headless: true });
    try {
        const directory = path.resolve(__dirname, '../../storage/app/private/email-previews');
        for (const name of ['contact', 'candidature']) {
            for (const width of [760, 390]) {
                const page = await browser.newPage({ viewport: { width, height: 900 }, deviceScaleFactor: 1 });
                await page.goto(pathToFileURL(path.join(directory, name + '.html')).href);
                await page.locator('img').evaluateAll(images => Promise.all(images.map(image => image.decode())));
                const sizes = await page.evaluate(() => ({ width: window.innerWidth, contentWidth: document.documentElement.scrollWidth }));
                if (sizes.contentWidth > sizes.width) throw new Error(`${name}: horizontal overflow at ${width}px`);
                const destination = path.join(directory, `${name}-${width}.png`);
                await page.screenshot({ path: destination, fullPage: true });
                console.log(destination);
                await page.close();
            }
        }
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
