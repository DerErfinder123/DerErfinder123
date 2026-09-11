import requests
liga_url = "https://api.openligadb.de/getmatchdata/bl1/"
antwort = requests.get(liga_url)
spiel = antwort.json()
print(spiel[:1])