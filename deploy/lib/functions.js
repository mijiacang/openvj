String.prototype.endsWith = function(suffix)
{
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

var fs = require('fs');

function bundleScript(dir)
{
    if (!dir.endsWith('/'))
        dir = dir + '/';
    
    
    var c = fs.readFileSync(dir + 'bundle.json').toString();
    c = JSON.parse(c);
    
    var content = '/*! Generated by Bundle-Script */\n';
    
    c.files.forEach(function(f)
    {
        console.log('+ ' + f);
        
        var d = fs.readFileSync(dir + f);
        content += '\n\n/*! ' + f + ' */\n';
        content += d + '\n';
    });
    
    var output = dir + c.output;
    console.log('> ' + output);
    
    fs.writeFileSync(output, content);
    
    return true;
}

global.Ex =
{
    BundleScript: bundleScript
};