wget "http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.0.jar"
nohup Xvfb :40 -ac &
export DISPLAY=:40
nohup java -jar selenium-server-standalone-2.53.0.jar > /tmp/selenium.log 2>&1 &
wget --retry-connrefused --tries=5 --waitretry=3 "http://127.0.0.1:4444/wd/hub/status"
if [ ! $? -eq 0 ]; then
    echo "Selenium server is not started properly"
else
    echo "Setup is done"
fi