wget "http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.0.jar"
nohup Xvfb :40 -ac &
export DISPLAY=:40
java -jar selenium-server-standalone-2.53.0.jar /tmp/selenium.log &