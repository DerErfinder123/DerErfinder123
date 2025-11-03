import arcade
import random

OFFSET = 50
ZELLEN_BREITE = 30
ANZAHL_ZELLEN = 24


class GameOfLife(arcade.Window):

    def __init__(self):
        super().__init__(800, 800, "Conway's Spiel des Lebens")
        self.background_color = arcade.color.GRAY

        self.setup()
        self.inloop = 0

    def setup(self):
        self.zellen = [[random.randint(0, 1) for _ in range(ANZAHL_ZELLEN)] for _ in range(ANZAHL_ZELLEN)]
        print(self.anzahl_nachbarn(0, 0))
    def nächste_population(self):
        #erstelle eine neue population 

        population = [[0 for _ in range(ANZAHL_ZELLEN)] for _ in range(ANZAHL_ZELLEN)]
        for y in range(ANZAHL_ZELLEN):
            for x in range(ANZAHL_ZELLEN):
                zelle = self.zellen[y][x]
                anzahl_nachbarn = self.anzahl_nachbarn(x, y)
                if zelle == 1:#lebendig
                    if anzahl_nachbarn in (2, 3):
                        population[y][x] = 1 #bleibt lebendig
                else: #tot
                    if anzahl_nachbarn == 3:
                        population[y][x] = 1 #wird lebendig
        self.zellen = population
        return population
    def on_mouse_press(self, x, y, button, modifiers):
        self.nächste_population()
    def on_key_press(self, key, modifiers):
        if key == arcade.key.SPACE:
            self.setup()
        elif key == arcade.key.KEY_0:
            if self.inloop == 1:
                self.inloop = 0
            else:
                self.inloop = 1
        else:
            self.nächste_population()
    def on_update(self, delta_time):
        if self.inloop == 1:
            self.nächste_population()
                    
    

    def anzahl_nachbarn(self, x: int, y: int) -> int:
        anzahl = 0
        richtungen = ((-1,-1), (0,-1), (1, -1),
                      (-1, 0), (1, 0),
                      (-1, 1), (0, 1), (1, 1))
        
        for rx, ry in richtungen:
            # Nachbarposition berechnen
            nx = x + rx
            ny = y + ry

            # liegt die Position innerhalb des Spiels
            # Wenn nicht, dann überspringen mit 'continue'
            if nx < 0 or ny < 0 or nx >= ANZAHL_ZELLEN or ny >= ANZAHL_ZELLEN:
                continue

            # Hole mir die Nachbarzelle
            nachbar = self.zellen[ny][nx]

            # Hochzählen wenn lebendig
            if nachbar == 1:
                anzahl += 1

        print(f"Anzahl Nachbarn von {x}, {y}: {anzahl}")
        return anzahl

    def on_draw(self):
        self.clear()

        for y in range(ANZAHL_ZELLEN):
            for x in range(ANZAHL_ZELLEN):
                zelle = self.zellen[y][x]

                links = OFFSET + x * ZELLEN_BREITE
                unten = OFFSET + y * ZELLEN_BREITE

                # male outline
                arcade.draw_lbwh_rectangle_outline(
                    left=links, 
                    bottom=unten, 
                    width=ZELLEN_BREITE, 
                    height=ZELLEN_BREITE,
                    color=arcade.color.BLACK,
                )

                # fülle zelle
                füll_farbe = arcade.color.BLACK if zelle else arcade.color.WHITE
                arcade.draw_lbwh_rectangle_filled(
                    left=links, 
                    bottom=unten, 
                    width=ZELLEN_BREITE, 
                    height=ZELLEN_BREITE,
                    color=füll_farbe
                )
                


spiel = GameOfLife()

arcade.run()
    