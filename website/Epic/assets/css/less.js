//Author: Joseph McCullough et al
//http://www.vertstudios.com/blog/less-app-windows-sorta/
//Let's the poor Windows users have some LESS fun ;)

var lessLibPath = '/cygdrive/d/nodejs/lib/lessjs';

var path = require('path'),
    fs = require('fs'),
    sys = require('sys');

//http://bit.ly/dKlPqQ (stack overflow) for package.json use
require.paths.unshift('./node_modules')

//snag the less module
var less = require(lessLibPath);

//Let the user know less is listening for file changes.
sys.puts("\n" + stylize("LESS - compiling & listening...", 'underline') + "\n");

//For each file in the current directory...
fs.readdirSync(__dirname).forEach(function (file) {
	
	//If it isn't a less file, break out.
    if (! /\.less/.test(file)) { return }

	var fullpath = __dirname + "/" + file;

	sys.puts(fullpath);


	//Convert less to CSS. Write to file if successful.
	toCSS(fullpath, function (err, less) {
			writeCSS(file,err,less);
	});
});


//For each file in the current directory...
fs.readdirSync(__dirname).forEach(function (file) {
	
	//If it isn't a less file, break out.
    if (! /\.less/.test(file)) { return }

	var fullpath = __dirname + "/" + file;
	
	//Add a file watch
	fs.watchFile(fullpath, { persistent: true, interval: 200}, function(curr,prev)
	{
		//Convert less to CSS. Write to file if successful.
		toCSS(fullpath, function (err, less) {
				writeCSS(file, err,less);
			});
	});
});

function toCSS(path, callback) {
    var tree, css;
    fs.readFile(path, 'utf-8', function (e, str) {
        if (e) { return callback(e) }

        new(less.Parser)({
            paths: [require('path').dirname(path)],
            optimization: 0
        }).parse(str, function (err, tree) {
            if (err) {
                callback(err);
            } else {
                try {
                    css = tree.toCSS({compress: true});
                    callback(null, css);
                } catch (e) {
                    callback(e);
                }
            }
        });
    });
}

function writeCSS(file,err,less){
	if (err) {sys.puts(stylize("ERROR: " + (err && err.message), 'red'));}
	else
	{
		var newFile = file.replace(/less/,"css");
		//var newDir = __dirname.substr(0, __dirname.lastIndexOf("/")+1) + "css/";
		//AlloVince fix, put new file to same dir
		var newDir = __dirname + "/";
		fs.writeFile(newDir + newFile, less, function(err)
		{
				if (err){
					sys.puts(err);
				} else {
					sys.puts(newFile + " successfully updated.");
				}
		}); 
	}
}


// Stylize a string
function stylize(str, style) {
    var styles = {
        'bold'      : [1,  22],
        'inverse'   : [7,  27],
        'underline' : [4,  24],
        'yellow'    : [33, 39],
        'green'     : [32, 39],
        'red'       : [31, 39]
    };
    return '\033[' + styles[style][0] + 'm' + str +
           '\033[' + styles[style][1] + 'm';
}


