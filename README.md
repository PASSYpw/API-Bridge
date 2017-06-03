# API-Bridge
The Api bridge is a very simple PHP Script, which aims to make use of passy without Cookies.

# How it Workes:
You use the normal passy HTTP POST System.
The difference is simple, on a login call, you will get a additional json field: token, this token represents the PHP Session ID.

Now when you make a request, you just simply add a form field: access_token and provide the php session ID, the script will handle the Cookie stuff
