# composer-shell
A silly reverse shell invoked via the Composer Dependency Manager.

## background
[Composer](https://getcomposer.org/), which is most probably *the* most popular PHP dependency manager allows for [scripts](https://getcomposer.org/doc/articles/scripts.md) to run as callbacks on based certain events.
Callbacks are normally triggered just before or after certain events.

It is possible to provide shell commands to the `scripts` property in the required `composer.json` file (with a few restrictions), but this method echoes the command that it executes.
A slightly more covert approach would be to execute a cleverly named static function in a class included in the codebase. It has to be one that can be autoloaded by composer.

## why?
Because I can. I thought a little about which scenarios this may actually be useful and figured maybe only strange edge cases where you can only run composer (as root lol?). I also remembered CVE-2014-9390 in [git](https://community.rapid7.com/community/metasploit/blog/2015/01/01/12-days-of-haxmas-exploiting-cve-2014-9390-in-git-and-mercurial) that allowed for code execution in 'poisoned' repositories via hooks. Well, I guess this is very similar.

## PoC
As part of the PoC, I just used the popular [pentest-monkey PHP reverse shell](http://pentestmonkey.net/tools/web-shells/php-reverse-shell), but really, anything is possible that is possible with PHP at this point.

[![asciicast](https://asciinema.org/a/b64qlhadvl7zn1912ihwi09wt.png)](https://asciinema.org/a/b64qlhadvl7zn1912ihwi09wt)
