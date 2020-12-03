# Speed Track

**Speed Track** is an application that monitors the speed of your internet line over a period.

## How it works

Every 5 minutes or so, the application runs a background `cron` job that checks the internet speed (upload and download) 
using the `speedtest-cli` binary. It saves this data to a local database and then exposes an API that allows such data to be viewed 
by any external client written in any language supporting RESTful APIs.


