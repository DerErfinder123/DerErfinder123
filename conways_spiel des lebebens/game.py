import arcade
import random
zellen_breite = 30
anzahl_zellen = 30
offset = 50
class Spiel(arcade.Window):
    def __init__(self):
        super().__init__(800,800,"conway's game of life")
        self.background_color = arcade.color.GRAY
        self.setup()
    def setup(self):
        self.zellen = [[random.randint(0,1) for _ in range(anzahl_zellen)] for _ in range(anzahl_zellen)]
    def on_draw(self):
        self.clear()

        for y in range(anzahl_zellen):
            for x in range(anzahl_zellen):
                zelle = self.zellen[y][x]
                # Berechne Kanten der Zelle (links, rechts, unten, oben)
                arcade.draw_lbwh_rectangle_filled(
                    offset + x * zellen_breite,
                    offset + y * zellen_breite,
                    zellen_breite - 1,
                    zellen_breite - 1,
                    arcade.color.BLACK if zelle == 1 else arcade.color.WHITE
                )



def main():
    spiel = Spiel()
    arcade.run()

if __name__ == "__main__":
    main()
