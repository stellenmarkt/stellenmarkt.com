# Stellenmarkt.com


## Intruduction

This is the Yawik Skin used at stellenmarkt.com. This module uses Wordpress for content on the landing pages. Also
otherwise many things are hard coded.

## Installation using composer

```bash
:~$ git clone https://github.com/stellenmarkt/stellenmarkt.com.git
:~$ cd stellenmarktt.com
:~stellenmarkt.com$ composer install
```

You'll need a mongo db. If you have no database, install the yawik/install
bodule. 

```bash
:~/stellenmarkt.com$ composer require yawik/install
```

Start the dev env

```bash
:~/stellenmarkt.com$ composer serve
```
