import requests
import pandas as pd
import time
import os
import json

nexttime = time.time()  # initializing

# switch to for i in range(2) to run finitely
# i=1
list6=[]

url1 = 'https://riderts.app/bustime/api/v3/getpatterns?key=QxNvqGVVVdD4k3LgZjUgEEDz3&rt=1&tmres=s&format=json'
#url2="https://riderts.app/bustime/api/v3/getvehicles?key=QxNvqGVVVdD4k3LgZjUgEEDz3&rt=12,13,15,16,17,20,23,25,26,33&tmres=s&format=json"
#url3="https://riderts.app/bustime/api/v3/getvehicles?key=QxNvqGVVVdD4k3LgZjUgEEDz3&rt=34,35,37,38,43,46,75,711,119,122&tmres=s&format=json"
#url4="https://riderts.app/bustime/api/v3/getvehicles?key=QxNvqGVVVdD4k3LgZjUgEEDz3&rt=125,126,127,150,600&tmres=s&format=json"

sesh = requests.Session()
req1 = sesh.get(url1)
data=(requests.Session()).get(url1).json()['bustime-response']
test = json.dumps(data, indent=4)
with open("data/patterns.json", "w") as outfile:
    outfile.write(test)
#file.write(data)