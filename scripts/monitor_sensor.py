#!/usr/bin/env python
import requests
import json
import datetime
import Adafruit_DHT
import time
import csv
import sys
import mysql.connector


DHT_SENSOR = Adafruit_DHT.DHT22
DHT_PIN =21
new_time = datetime.datetime.now()
date_str = new_time.strftime("%Y-%m-%d %I:%M:%S")
who = 'API'
try:
    connection = mysql.connector.connect(host='localhost',
                                         database='webcron',
                                         user='root',
                                         password='0rang3T3ch4758!DB')

    sql_select_Query = "select * from customer"
    cursor = connection.cursor()
    cursor.execute(sql_select_Query)
    records = cursor.fetchall()

    for row in records:
        rs_customer_id = row[1]
        device_id = row[8]
        print "Customer ID:" , rs_customer_id
        print "Device ID:", device_id
	print "Status Date:", date_str
except Error as e:
    print("Error reading data from MySQL table", e)
finally:
    if (connection.is_connected()):
        connection.close()
        cursor.close()
#        print("MySQL connection is closed")



#while True:
humidity, temperature = Adafruit_DHT.read(DHT_SENSOR, DHT_PIN)
if humidity is not None and temperature is not None:
        humidity = round(humidity,1)
        temperature = round(temperature,1)
        Fahrenheit = 9.0/5.0 * temperature + 32
#        print("Temp={0:0.1f}C Humidity={1:0.1f}%".format(temperature, humidity))
        timeC = time.strftime("%I")+':' +time.strftime("%M")+':'+time.strftime("%S")
        data = [temperature, humidity, timeC]
        print "Fahrenheit:", Fahrenheit
else:
        print("Sensor failure. Check wiring.");


obj = {'device_id': device_id, 'rs_customer_id': rs_customer_id, 'who': who, 'status_date': date_str, 'sensor_temp': Fahrenheit, 'sensor_humidity': humidity}

#print(response.status_code)

url = 'http://mps.copiers4sale.com/api/monitor/sensor.php'


x =  requests.post(url, json = obj)

#print(x.text)