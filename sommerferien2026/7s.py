variabeln = {}
with open("programm.7s","r") as file:
    lines = file.readlines()
def auswerten(ausdruck):
    ausdruck_teile = ausdruck.split(" ")
    if len(ausdruck_teile) == 1:
        if ausdruck in variabeln:
            return int(variabeln[ausdruck])
        else:
            return int(ausdruck)
    return auswerten(ausdruck_teile[0]) + auswerten(ausdruck_teile[2])
        


for line in lines:
    line = line.strip()
    if line == "" or line.startswith("ß"):
        continue
    elif line.startswith("print"):
        print(auswerten(line[6:]))

    elif line.startswith("let"):
        variabeln[line.split(" ")[1]] = auswerten(line[(line.index("=")+ 2):])


    print(line)
    print(variabeln)

    
