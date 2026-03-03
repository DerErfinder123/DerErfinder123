extends CharacterBody3D
@export var speed = 5
@export var fall_acceleration = 75
@export var jump_impuls = 20
@export var bounce_impuls = 16

var target_velocity = Vector3.ZERO
func _input(event):
	# Mouse in viewport coordinates.
	if event is InputEventMouseButton:
		print("Mouse Click/Unclick at: ", event.position)
	elif event is InputEventMouseMotion:
		print("Mouse Motion at: ", event.position)


func _physics_process(delta: float) -> void:
	var direction = Vector3.ZERO

	
	if Input.is_action_pressed("move_right"):
		direction.x += 1
	if Input.is_action_pressed("move_left"):
		direction.x -= 1
	if Input.is_action_pressed("move_back"):
		direction.z += 1
	if Input.is_action_pressed("move_forward"):
		direction.z -= 1
		
	if direction != Vector3.ZERO:
		direction = direction.normalized()
		$".".basis = Basis.looking_at(direction)
	target_velocity.x = direction.x * speed
	target_velocity.z = direction.z * speed
	
	if not is_on_floor():
		target_velocity.y = target_velocity.y - (fall_acceleration * delta)
	if is_on_floor() and Input.is_action_just_pressed("jump"):
		target_velocity.y = jump_impuls
		
	
	for index in range(get_slide_collision_count()):
		var collision = get_slide_collision(index)
		if collision.get_collider() == null:
			continue
		
		if collision.get_collider().is_in_group("mob"):
			var mob = collision.get_collider()
			print(1111111)
			if Vector3.UP.dot(collision.get_normal()) > 0.1:
				print("wwwwwwww")
				mob.squash()
				target_velocity.y = bounce_impuls
				break
	velocity = target_velocity
	move_and_slide()
