import random

def spiel(manschaft1, manschaft2, stärke):
    gesamstärke = stärke[manschaft1] + stärke[manschaft2]
    gewinner = manschaft1 if random.randint(0, gesamstärke) <= stärke[manschaft1] else manschaft2
    print(manschaft1, "vs", manschaft2,"->", gewinner)
    print(gesamstärke)
    return gewinner


manschaften = ["Bayern", "Dortmund", "Schalke", "Stutgart", "Leverkurse", "Frankfurt", "Leipzig", "Freiburg"]
stärken = {"Bayern": 95, "Dortmund": 92, "Schalke": 86, "Stutgart": 82, "Leverkurse": 89, "Frankfurt": 75, "Leipzig": 90, "Freiburg": 74}

random.shuffle(manschaften)
while len(manschaften) > 1:
    gewinner_liste = []
    for i in range(0, len(manschaften), 2):
        gewinner = spiel(manschaften[i], manschaften[i + 1], stärken)
        gewinner_liste.append(gewinner)
    print(gewinner_liste)
    manschaften = gewinner_liste






