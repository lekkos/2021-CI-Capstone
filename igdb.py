#IGDB API access
#Swadesh Malneedi

import requests
import json
import mysql.connector


mydb = mysql.connector.connect(
  user="swadesh",
  password="catProgrammer73",
  host="localhost",
  database="capstone_db"
)
mycursor = mydb.cursor()

res = requests.post('https://id.twitch.tv/oauth2/token?client_id=khkc6i5cwvbln0ki3wo9u5mtw4qj64&client_secret=5b73meb9a1uugfop5ft4lge5tx7nyd&grant_type=client_credentials')
result = res.json()
headers = {
  'Client-ID': 'khkc6i5cwvbln0ki3wo9u5mtw4qj64',
  'Authorization': 'Bearer ' + result['access_token']
} 
url = requests.get('https://api.igdb.com/v4/games?fields=*', headers=headers)

#url = requests.get('https://api.igdb.com/v4/companies?fields=*', headers=headers)
json_data = url.json()

#print(json_data)
 

#Example professor showed me during meeting.
for i in range(0,10):
  name = json_data[i]['name']
  slug = json_data[i]['slug']
  summary = json_data[i]['summary'] 
  print(name)
  print(slug)
  sql = "INSERT INTO games (column1, column2, column3) VALUES (%s,%s,%s)"
  val = (name, slug, summary)
  mycursor.execute(sql,val)
  mydb.commit()
  print("record inserted.")


 
