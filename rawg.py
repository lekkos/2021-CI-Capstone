#Stephen Lekko
#Computing and Informatics Capstone
#This is the file used for making a connection to RAWG.io
#key = 24cad30c5b3949ff9140d8617254ff06

import requests
import json
import mysql.connector

#START DB
userin = input("Enter the Game Name: ")
mydb = mysql.connector.connect(
    host = "localhost",
    user = "stephen", 
    passwd = "cowAttorney48",
    database = "capstone_db"
)

mycursor = mydb.cursor()
mycursor.execute("use capstone_db")

url = "https://api.rawg.io/api/games?key=24cad30c5b3949ff9140d8617254ff06&page_size=1&search=" + userin;

resultCount = 0

while resultCount < 1:
    r = requests.get(url)
   # print(url)
    data = json.loads(r.text)
    url = data['next']
    for game in data['results']:
        sql = "INSERT INTO rawg_api (rawg_id, rawg_name, rawg_slug, rawg_release_date, rawg_metacritic) VALUES (%s, %s, %s, %s, %s )"
        val = (game['id'], game['name'], game['slug'], game['released'], game['metacritic'])
        mycursor.execute(sql,val)
        mydb.commit()
        print(mycursor.rowcount,"record inserted.")
        print('Game Name: ',game['name'])
        print('Game Slug: ',game['slug'])
        print('Release Date: ',game['released'])
        print('Game ID: ', game['id'])
        print('Metacritic Score: ',game['metacritic'])
        print('-------------------------------------')
        resultCount += 1
        
#END DB



        
