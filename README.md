# Nexus Aurora Plugin

Nexus Aurora Plugin used for Discord Bot administration and other custom functionality.

## Description
This plugin requires our [Discord Bot](https://github.com/semc-labs/NexusBot)

This plugin will display discord server statistics.

Eventually actions will be taken from admin.

Many potential features.

## Installation
Download this repo as a zip file and upload it as a New Plugin.

After installing and activating you will need to update the "Nexus_Aurora_Bot_URL" variable in the plugin settings. This is the url the discordbot express server is available at.

In order for the WordPress bot to post announcements you will need to install the [JWT Authentication for WP REST API](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/) plugin.

Then create a user specifically for the bot which has permissions limited to posting. Then make sure to update the .env file with this bot's credentials

You will also need to add a secrect constant to your wp-config.php like: define('JWT_AUTH_SECRET_KEY', 'supersecretstring');

Then add your 'supersecretstring' to your NexusBot .env file

### Frequently Asked Questions 

*How do I setup the discord bot?*

Go to our [Discord Bot](https://github.com/semc-labs/NexusBot) repo for more answers
