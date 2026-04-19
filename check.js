const fs = require('fs');
const content = fs.readFileSync('d:/MediNear/MEDINEAR2/resources/views/welcome.blade.php', 'utf8');
const lines = content.split('\n');
let count = 0;
lines.forEach((l, i) => {
    if (/[\u0600-\u06ff]/.test(l) && !l.includes('data-i18n') && !l.includes('i18n') && !l.includes('//') && !l.includes('content=')) {
        console.log((i+1) + ': ' + l.trim());
        count++;
    }
});
console.log('Total un-translated lines:', count);
