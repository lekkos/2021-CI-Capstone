#Stephen Lekko
#Computing and Informatics Capstone
#This is the file used for making a connection to RAWG.io
#key = 24cad30c5b3949ff9140d8617254ff06

import requests
import json
import mysql.connector

#user_input_title = input("Please Enter The Name of a Game: ")
#3.11.21 Issue with search precise, returning only GTAV

#START DB
mydb = mysql.connector.connect(
    host = "localhost",
    user = "root", 
    passwd = "",
    database = "lekkos_api_db"
)

mycursor = mydb.cursor()
#mycursor.execute("CREATE DATABASE apiconnectiondb")
mycursor.execute("use lekkos_api_db")
#mycursor.execute("CREATE TABLE vgdata (vg_id INT PRIMARY KEY, vg_name VARCHAR(255), vg_release_date DATE, metacritic FLOAT)")

url = "https://api.rawg.io/api/games?key=24cad30c5b3949ff9140d8617254ff06&page_size=1&search=Splatoon"

resultCount = 0

while resultCount < 1:
    r = requests.get(url)
   # print(url)
    data = json.loads(r.text)
    url = data['next']
    for game in data['results']:
        sql = "INSERT INTO vg_data (vg_id, vg_name, vg_release_date, vg_metacritic) VALUES (%s, %s, %s, %s )"
        val = (game['id'], game['name'], game['released'], game['metacritic'])
        mycursor.execute(sql,val)
        mydb.commit()
        print(mycursor.rowcount,"record inserted.")
        print('Game Name: ',game['name'])
        print('Release Date: ',game['released'])
        print('Game ID: ', game['id'])
        print('Metacritic Score: ',game['metacritic'])
        print('-------------------------------------')
        resultCount += 1
        
#END DB



        