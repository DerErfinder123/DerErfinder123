extends Node3D
@export var mob_scence: PackedScene


func _on_mob_timer_timeout() -> void:
	var 	mob = mob_scence.instantiate()# Replace with function body.
	
	var mob_spawn_location = $Path3D/SpawnLocation
	mob_spawn_location.progress_ratio = randf()
	var player_position = $player.position
	mob.initialize(mob_spawn_location.position, player_position)
	
	add_child(mob)
