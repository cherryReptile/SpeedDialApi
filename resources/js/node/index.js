const puppeteer = require('puppeteer');
const args = process.argv.slice(2);
const path = require('path');
let imgPath = 'img/' + args[1].toString() + '.png';

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(args[0].toString());
    await page.screenshot({path: imgPath});

    await browser.close();
})();
let absImgPath = __dirname + '/' + imgPath;
console.log(absImgPath.toString());