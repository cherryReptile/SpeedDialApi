const puppeteer = require('puppeteer-core');
const argsProcess = process.argv.slice(2);
let imgPath = 'speeddials/' + argsProcess[1].toString() + '.png';

try{
(async () => {
    const browser = await puppeteer.launch({
        executablePath: '/usr/bin/google-chrome-stable',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    await page.goto(argsProcess[0].toString());
    await page.screenshot({path: '/var/www/storage/app/' + imgPath});

    await browser.close();
    await process.exit();
})();} catch (err){
    process.exit();
}