extends CharacterBody3D
@export var sensitivity = 0.002 # Wie schnell die Kamera dreht
@onready var head =$"../head"

@onready var health_bar = $"../CanvasLayer/ProgressBar"

var health = 100

func take_damage(amount: int):
	health -= amount
	health_bar.value = health  # Aktualisiert den Balken sofort
func regenerate(amount: int):
	health += amount
	health_bar.value = health

func _ready():
	Input.mouse_mode = Input.MOUSE_MODE_CAPTURED # Maus verstecken & fixieren

const SPEED = 5.0
const JUMP_VELOCITY = 4.5
var translation = Vector3.ZERO

func _physics_process(delta: float) -> void:
	# Add the gravity.
	if not is_on_floor():
		velocity += get_gravity() * delta

	# Handle jump.
	if Input.is_action_just_pressed("jump") and is_on_floor():
		velocity.y = JUMP_VELOCITY

	# Get the input direction and handle the movement/deceleration.
	# As good practice, you should replace UI actions with custom gameplay actions.
	var input_dir := Input.get_vector("move_left", "move_right", "move_forward", "move_back")
	var direction := (transform.basis * Vector3(input_dir.x, 0, input_dir.y)).normalized()
	if direction:
		velocity.x = direction.x * SPEED
		velocity.z = direction.z * SPEED
	else:
		velocity.x = move_toward(velocity.x, 0, SPEED)
		velocity.z = move_toward(velocity.z, 0, SPEED)

	move_and_slide()
	if Input.is_action_just_pressed("health_+"):
		regenerate(1)
	if Input.is_action_just_pressed("health_-"):
		take_damage(1)
func _unhandled_input(event):
	# Maus mit ESC wieder freigeben
	if event.is_action_pressed("ui_cancel"): # "ui_cancel" ist standardmäßig ESC
		Input.mouse_mode = Input.MOUSE_MODE_VISIBLE
	
	# Erneuter Klick ins Fenster fängt die Maus wieder ein
	if event is InputEventMouseButton and event.pressed:
		Input.mouse_mode = Input.MOUSE_MODE_CAPTURED
	if event is InputEventMouseMotion:
		# Horizontale Drehung des Körpers
		rotate_y(-event.relative.x * sensitivity)
		# Vertikale Drehung des Kopfes
		head.rotate_x(-event.relative.y * sensitivity)
		# Begrenzung (Clamping)
		head.rotation.x = clamp(head.rotation.x, deg_to_rad(-89), deg_to_rad(89))
	

	
