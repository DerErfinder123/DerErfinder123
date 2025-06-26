import socket
import threading

server_ip = "0.0.0.0"
server_port = 8000

server_adress = (server_ip, server_port)
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(server_adress)
server.listen(2)  # Maximal 2 Verbindungen

client_name = dict()

def handle_client(client_socket, client_address):
    client_socket.send("Willkommen auf dem Server! Bitte gib deinen Namen ein: ".encode("utf-8"))
    name = client_socket.recv(1024).decode("utf-8")
    client_name[client_address] = name
    print(f"Client {client_address} verbunden als {name}")
    while True:
        request = client_socket.recv(1024)
        client_socket.send()

print("Server läuft...")

while True:
    client_socket, client_address = server.accept()
    print("Client verbunden:", client_address)
    thread = threading.Thread(target=handle_client, args=(client_socket, client_address))
    thread.start()








