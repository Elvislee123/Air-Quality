import serial, time
import mysql.connector
import requests
import MySQLdb
from bs4 import BeautifulSoup

mydb = mysql.connector.connect(
host="localhost",
user="user1",
passwd="password",
database="airquality"
)

mycursor = mydb.cursor()

ser = serial.Serial('/dev/ttyUSB0')

while True:
	data = []
	for index in range(0,10):
		datum = ser.read()
		data.append(datum)
	pmtwofive = int.from_bytes(b''.join(data[2:4]), byteorder='little') /10
	pmten = int.from_bytes(b''.join(data[4:6]), byteorder='little') /10
	print("The PM2.5 is {}".format(pmtwofive))
	print("The PM10 is {}".format(pmten))
	url_to_scrape = 'https://aqicn.org/city/london/'

	plain_html_text = requests.get(url_to_scrape)
	soup = BeautifulSoup(plain_html_text.text, "html.parser")

	cur_pm25 = soup.find('td', id="cur_pm25")
	cur_pm10 = soup.find('td', id="cur_pm10")
	Ldn_pm25 = cur_pm25.text
	Ldn_pm10 = cur_pm10.text
	sqlquery = "INSERT INTO `Air_quality_data` (`PM2.5 sensor`, `PM10 sensor`, `PM2.5 London`, `PM10 London`) VALUES (%s, %s, %s, %s)"
	mycursor.execute(sqlquery, (float(pmtwofive), float(pmten), float(Ldn_pm25), float(Ldn_pm10)))
	mydb.commit()
	print(mycursor.rowcount, "record inserted")
	time.sleep(3600)

