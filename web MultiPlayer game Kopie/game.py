import arcade
import arcade.camera
class game(arcade.Window):
    def __init__(self):
        super().__init__(500,500, "game")
        self.layer_options = {
            "hindernis": {
                "use_spatial_hash": True
            }
        }
        
        self.tile_map = arcade.load_tilemap("map.tmx",scaling=1, layer_options=self.layer_options)
        self.scene = arcade.Scene().from_tilemap(self.tile_map)
        self.spieler = arcade.Sprite("spieler.png", 1)
        self.spieler.center_x = 100
        self.spieler.center_y = 100
        self.scene.add_sprite("spieler", self.spieler)
        self.camera = arcade.camera.Camera2D()
        

    def on_draw(self):
        self.clear()
        self.camera.use()  # Kamera zuerst setzen
        self.scene.draw()
    def on_update(self, delta_time):
        
        

        self.scene.update(delta_time)
        self.spieler.update()
        
        # Spielfeldgröße bestimmen (hier als Beispiel 2000x2000, passe ggf. an)
        map_width = self.tile_map.width * self.tile_map.tile_width
        map_height = self.tile_map.height * self.tile_map.tile_height

        # Kamera folgt dem Spieler, bleibt aber im Fenster
        cam_x = max(self.width // 2, min(self.spieler.center_x, map_width - self.width // 2))
        cam_y = max(self.height // 2, min(self.spieler.center_y, map_height - self.height // 2))
        self.camera.position = (cam_x, cam_y)

        # print(self.spieler.position)
        # print(self.camera.position)
        






    def on_key_press(self, symbol, modifiers):
        if symbol == arcade.key.W or symbol == arcade.key.UP:
            self.spieler.change_y = 5
        elif symbol == arcade.key.S or symbol == arcade.key.DOWN:
            self.spieler.change_y = -5
        elif symbol == arcade.key.A or symbol == arcade.key.LEFT:
            self.spieler.change_x = -5
        elif symbol == arcade.key.D or symbol == arcade.key.RIGHT:
            self.spieler.change_x = 5
    def on_key_release(self, symbol, modifiers):
        if symbol == arcade.key.W or symbol == arcade.key.UP:
            self.spieler.change_y = 0
        elif symbol == arcade.key.S or symbol == arcade.key.DOWN:
            self.spieler.change_y = 0
        elif symbol == arcade.key.A or symbol == arcade.key.LEFT:
            self.spieler.change_x = 0
        elif symbol == arcade.key.D or symbol == arcade.key.RIGHT:
            self.spieler.change_x = 0

def main():
    game()
    arcade.run()
if __name__ == "__main__":
    main()


