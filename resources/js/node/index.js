const puppeteer = require('puppeteer-core');
const argsProcess = process.argv.slice(2);
const path = require('path');
let imgPath = 'img/' + argsProcess[1].toString() + '.png';

(async () => {
    const browser = await puppeteer.launch({
        executablePath: '/usr/bin/google-chrome-stable',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    await page.goto(argsProcess[0].toString());
    await page.screenshot({path: imgPath});

    await browser.close();
})();