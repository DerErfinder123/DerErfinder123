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
        self.scene.draw()
        self.camera.use()
    def on_update(self, delta_time):
        
        

        self.scene.update(delta_time)
        self.spieler.update()
        
        #print(self.spieler.position)
        self.camera.position = self.spieler.position
        #if self.camera.position[0] -250 > 500:
         #   self.camera.position = (0, self.spieler.position[1])
        #if self.camera.position[1] - 250 > 500:
         #   self.camera.position = (self.spieler.position[0], 0)
        #if self.camera.position[0] + 250 < 0:
           # self.camera.position = (500, self.spieler.position[1])
        #if self.camera.position[1] + 250 < 0:
         #   self.camera.position = (self.spieler.position[0], 500)
        print(self.camera.position)
        
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

        
