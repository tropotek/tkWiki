# tkWiki 

__Project:__ tkWiki  
__Published:__ 16 May 2016  
__Web:__ <http://www.tropotek.com/>  
__Authors:__ Michael Mifsud <http://www.tropotek.com/>  

A WIKI that is easy to use for people that want a website to store info and
be easily accessible.

## Contents

- [Installation](#installation)
- [Introduction](#introduction)

## Installation

Start by getting the tkWiki source files from the repository

~~~bash
# git clone https://github.com/tropotek/tkWiki.git tkWiki
~~~

or download the zip and un-compress it in the required folder.

Then download [composer](https://getcomposer.org/download/) in run the following:

~~~bash
# cd tkWiki
# composer install
~~~

You will be prompted for your DB details and other site config settings.

Once this is completed you can browse your newly installed wiki.

If at any time you have an error just re-run the composer install command
this will delete any previous config and you can change the setup as desired.

To upgrade, update the tkWiki sources from git 

~~~bash
# cd tkWiki
# git pull
# composer update
~~~

Or alternatively download the zip and extract it to your tkWiki folder and then run the composer update.

Be sure to check out the `/src/config/config/php` file if you want do add/remove any settings for the tkWiki.
This file will not be removed during updates.


__Upgrading From ver 1.* to ver 2.*__

There is a file in bin/mysql_1.6-2.0.sql that can be used to migrate old tkWiki databases. Howerver it may
not work for all instances and may need a little adjustment for your version, so before running it backup 
your existing database, execute the file and check for any errors, if there are errors you can update the
migration sql and restore the database and try again. 

The only issues I have found so fare is that an index key may not exist and you have to comment our a line in the
migration file or rename the index name. other than that it should work fine.


Please leave any issues at GitHub (https://github.com/tropotek/tkWiki/issues)

## Introduction

// TODO

