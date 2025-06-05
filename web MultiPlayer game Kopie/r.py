        map_width = self.tile_map.width * self.tile_map.tile_width
        map_height = self.tile_map.height * self.tile_map.tile_height

        # Kamera folgt dem Spieler, bleibt aber im Fenster
        cam_x = max(self.width // 2, min(self.spieler.center_x, map_width - self.width // 2))
        cam_y = max(self.height // 2, min(self.spieler.center_y, map_height - self.height // 2))
        self.camera.position = (cam_x, cam_y)