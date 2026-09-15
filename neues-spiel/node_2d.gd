extends Node2D

var rng = RandomNumberGenerator.new()
var memo = {}


# Called when the node enters the scene tree for the first time.
func _ready() -> void:
	var i = 0
	var summe = 0
	#while i * i < 10000:
		#i += 1
		#print(i * i)
		#summe += i * i
	#print(summe)
	
	#stats_zufallszahlen(100, 1, 100)
	#print(fakultät(5))
	# 0 1 1 2 3 5 8 13 21 34 55
	
	print(fibonacci(10))
	

# Generiert n viele Zufallszahlen zwischen a und b und gibt aus:
# - Die Summe dieser Zufallszahlen
# - Das Maximum dieser Zufallszahlen
# - Das Minimum dieser Zufallszahlen
# - Den Durchschnitt dieser Zufallszahlen
# - Den Median dieser Zufallszahlen
func stats_zufallszahlen(n: int, a: int, b: int) -> void:
	var summe = 0
	var max = a
	var min = b
	for i in range(n):
		var zz = rng.randi_range(a, b)
		summe += zz
		if zz > max:
			max = zz
		if zz < min:
			min = zz
		
	print("Summe: ", summe)
	print("Maximum: ", max)
	print("Minimum: ", min)
	print("Durchschnitt: ", summe / n)

		
	# TODO Median
func fakultät(n: int) -> int:
	if n == 0:
		return(1)
	var vorgänger_argument = n - 1
	var fakultät_vorgänger_argument = fakultät(vorgänger_argument)
	var fakultät_argument = n * fakultät_vorgänger_argument
	return fakultät_argument
	print("t")
func fibonacci(n: int) -> int:
	if memo.has(n):
		return memo[n]
	if n == 0:
		return 0
	elif n == 1:
		return 1
			
	var vorgänger = fibonacci(n - 1)
	var vorvorgänger = fibonacci(n - 2)
	var ergebniss = vorgänger + vorvorgänger
	memo[n] = ergebniss
	return ergebniss
	
func zusammenfügen(sortierte_liste1: Array, sortierte_liste2: Array) ->Array:
	var gesamtliste = []
	for i in range(len(sortierte_liste1) + len(sortierte_liste2)):
		if sortierte_liste1[0] > sortierte_liste2[0]:
			gesamtliste.append(sortierte_liste2[0])
			sortierte_liste2.remove_at(0)
		else:
			gesamtliste.append(sortierte_liste1[0])
			sortierte_liste1.remove_at(0)
		if len(sortierte_liste1) == 0:
			gesamtliste += sortierte_liste2
			return gesamtliste
		elif len(sortierte_liste2) == 0:
			gesamtliste += sortierte_liste1
			return gesamtliste
	return gesamtliste
	
func teilliste(liste: Array,von: int,bis :int):
	var res = []
	for i in range(von, bis):
		res.append(liste[i])
	return res
#func sortieren4(liste: Array) -> Array:
#	if len(liste) <= 1:
#		return liste
#	var liste_teil1 = teilliste(liste,0,len(liste)/ 2)
#	var liste_teil2 = teilliste(liste, len(liste)/ 2 ,len(liste))
	#return zusammenfügen(sortieren4(teil)
#	if len(liste_teil1) > 1:
#		sortieren4(liste_teil1)
#	if len(liste_teil2) > 1:
#		sortieren4(liste_teil2)
	
func quersumme(n: int) -> int:
	if n < 10:
		return n 
	var  vorgänger = n / 10
	var quersumme_vorgänger = quersumme(vorgänger)
	var letzte_ziffer = n % 10
	print(letzte_ziffer)
	return quersumme_vorgänger + letzte_ziffer
#func sortieren4()
	
	
		
		
	

# Called every frame. 'delta' is the elapsed time since the previous frame.
func _process(delta: float) -> void:
	pass
