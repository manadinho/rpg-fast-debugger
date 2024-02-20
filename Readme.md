# rpg-fast-debugger

By using `RPG Fast Debugger`, you can expedite the process of troubleshooting PHP code and resolving issues.
First install `Fast Debugger` desktop application according to your operating system.
Now you are ready to receive log data from `PHP` projects.

## download desktop application
[mac](https://drive.google.com/file/d/1LKXWI8x8jiLawN5b9qmv_pV3djYsAzh6/view?usp=share_link).
[windows](https://drive.google.com/file/d/1AmpOiaD7kWe1DetkNWuVTE4TNb6647Dq/view?usp=share_link).
[linux](https://drive.google.com/file/d/1zDwRCBDEgDSAYlzS4gD_8o6wKRkfDe4f/view?usp=share_link).


## installation

    composer require manadinho/rpg-fast-debugger --dev
    
## Usage

To use `RPG Fast Debugger` in your `RPG-PHP` project. Just go to your `library/global_functions.php` and place following method there. This method takes three arguments. 

First argument is supposed to be array, in this array you can pass as many variables you want to inspect.

Second argument is optional. You can pass any flag (string) to it to make it easy to find your log on Fast Debugger desktop app.

Third argument is optional. If application is not running in docker you can pass the host to it. Which will be `localhost` in that case.
```php
function ezFast(Array $data, $flag="", $host="host.docker.internal") {
    if ( RPG_LOCAL ) {
        return new Manadinho\RPGFastDebugger\RPGFast($host, $flag, $host);
    }
}
```

Now simply use ezFast method to debug the app.

```php
ezFast([$var1, $var2], $flag, $host);
```

## Note

On log data you can see file name and line number from the `ezFast()` method is called. You can open file in `VSCODE` by simply clicking on file name.
