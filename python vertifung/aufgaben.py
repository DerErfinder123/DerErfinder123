def summe(zahlen: list[float]) -> float:
    summe = 0
    for zahl in range(len(zahlen)):
        summe += zahlen[zahl]
    return summe


def mittelwert(zahlen: list[float])-> float:
    return summe(zahlen) / len(zahlen)

def median(zahlen: list)->  float:
    zahlen.sort()
    if len(zahlen) % 2 == 1:
        return zahlen[len(zahlen) // 2]
    else:
        return (zahlen[len(zahlen)  // 2 -1 ] + zahlen[len(zahlen) // 2 ]) / 2

print(median([2, 6, 7, 10, 11, 78 ]))
    