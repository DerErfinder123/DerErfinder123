import arcade
class game(arcade.Window):
    def __init__(self):
        super().__init__(500,500, "game")
        self.layer_options = {
            "hindernis": {
                "use_spatial_hash": True
            }
        }
        self.spieler_liste = arcade.SpriteList()
        self.tile_map = arcade.load_tilemap("map.tmx",scaling=1, layer_options=self.layer_options)
        self.spieler = arcade.Sprite("spieler.png", 1)
        self.spieler.center_x = 100
        self.spieler.center_y = 100
        self.spieler_liste.append(self.spieler)
    def on_draw(self):
        self.clear()
        self.spieler_liste.draw()
        self.tile_map.draw()
    def on_update(self, delta_time):
        self.spieler_liste.update()

def main():
    game()
    arcade.run()
if __name__ == "__main__":
    main()

        
