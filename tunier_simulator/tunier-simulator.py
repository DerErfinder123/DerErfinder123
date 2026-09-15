import random
import requests
liga_url = "https://api.openligadb.de/getmatchdata/bl1/2025"
antwort = requests.get(liga_url)
spiele = antwort.json()
manschaften = []

def manschaft_anlegen(name):
    for manschaft in manschaften:
        if name == manschaft["name"]:
            return

    manschaft.append({"name": name, "stärke": 0})

for spiel in spiele:
    if not spiel["matchIsFinished"]:
        continue

    heim = spiel["team1"]["teamName"]
    gast = spiel["team2"]["teamName"] 
    manschaft_anlegen(heim)
    manschaft_anlegen(gast)

    heim_tore = spiel["matchResult"][-1]["pointsTeam1"]
    gast_tore = spiel["matchResult"][-1]["pointsTeam1"]

    for mannschaft in manschaften:
        if heim == mannschaft["name"]:
            mannschaft["stärke"] += heim_tore
        elif gast == mannschaft["name"]:
            mannschaft["stärke"] += gast_tore
print(spiele[:1])



def spiel(manschaft1, manschaft2):
    gesamstärke = manschaft1["stärke"] + manschaft2["stärke"]
    gewinner = manschaft1 if random.randint(0, gesamstärke) <= manschaft1["stärke"] else manschaft2
    print(manschaft1, "vs", manschaft2,"->", gewinner)
    print(gesamstärke)
    return gewinner


#manschaften = ["Bayern", "Dortmund", "Schalke", "Stutgart", "Leverkurse", "Frankfurt", "Leipzig", "Freiburg"]
#stärken = {"Bayern": 95, "Dortmund": 92, "Schalke": 86, "Stutgart": 82, "Leverkurse": 89, "Frankfurt": 75, "Leipzig": 90, "Freiburg": 74}

random.shuffle(manschaften)
while len(manschaften) > 1:
    gewinner_liste = []
    for i in range(0, len(manschaften), 2):
        gewinner = spiel(manschaften[i], manschaften[i + 1])
        gewinner_liste.append(gewinner)
    print(gewinner_liste)
    manschaften = gewinner_liste






