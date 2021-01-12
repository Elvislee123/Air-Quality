#!/var/www/html python3

import sys
import time as TT
import mysql.connector
import requests
import MySQLdb
from bs4 import BeautifulSoup
from datetime import datetime

mydb = mysql.connector.connect(
host="localhost",
user="user1",
passwd="password",
database="airquality"
)

mycursor = mydb.cursor()

url_to_scrape = 'https://www.iqair.com/us/world-air-quality'

while True:
	plain_html_text = requests.get(url_to_scrape)
	soup = BeautifulSoup(plain_html_text.text, "html.parser")

	tags = []
	cur_pm25 = soup.find_all('p', class_= 'city')
	for tag in cur_pm25:
		tags.append(str(tag))

	tags[0].replace(' ','')

	first = []
	last = []
	first_real = []
	last_real = []
	for i in range(len(tags[0])):
		if tags[0][i] == '<':
			first.append(i)
		if tags[0][i] == '>':
			last.append(i)

	for value in range(len(first)-1):
		if first[value+1] == last[value]+1:
			continue
		else:
			first_real.append(first[value+1])
			last_real.append(last[value]+1)

	Clean_city = tags[0][last_real[1]:first_real[1]]
	print(Clean_city)

	time = datetime.now()
	current_time = time.strftime("%H:%M:%S")
	print(current_time)

	link = 'https://www.google.com/search?q=flights+to+' + Clean_city 

	sqlquery = "INSERT INTO `Cleanest_Cities` (`Cleanest city`, `link`, `Time`) VALUES (%s, %s, %s)"
	mycursor.execute(sqlquery, (str(Clean_city), str(link), str(current_time)))
	mydb.commit()
	print(mycursor.rowcount, "record inserted")
	TT.sleep(30)
