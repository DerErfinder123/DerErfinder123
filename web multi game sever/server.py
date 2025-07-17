import socket
import threading

server_ip = "0.0.0.0"
server_port = 8000

server_adress = (server_ip, server_port)
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(server_adress)
server.listen(2)  # Maximal 2 Verbindungen gleichzeitig

client_name = dict()
position_1 = "0,0"
position_2 = "3,5"
client2_online = False

def handle_client1(client_socket, client_address):
    global position_1, position_2, client2_online
    try:
        client_socket.send("Willkommen auf dem Server! Bitte gib deinen Namen ein: ".encode("utf-8"))
        name = client_socket.recv(1024).decode("utf-8")
        client_name[client_address] = name
        print(f"Client {client_address} verbunden als {name}")
        while True:
            request = client_socket.recv(1024)
            position_1 = request.decode("utf-8")
            client_socket.send(position_2.encode("utf-8"))
            print(f"Position von {name}: {position_1}")
    except Exception as e:
        print(f"Fehler bei der Verbindung mit {client_address}: {e}")
    finally:
        print(f"Verbindung mit {client_address} geschlossen.")
        client_socket.close()
        del client_name[client_address]

def handle_client2(client_socket, client_address):
    global position_1, position_2, client2_online
    try:
        client_socket.send("Willkommen auf dem Server! Bitte gib deinen Namen ein: ".encode("utf-8"))
        name = client_socket.recv(1024).decode("utf-8")
        client_name[client_address] = name
        print(f"Client {client_address} verbunden als {name}")
        client2_online = True
        while True:
            request = client_socket.recv(1024)
            position_2 = request.decode("utf-8")
            client_socket.send(position_1.encode("utf-8"))
            print(f"Position von {name}: {position_2}")
    except Exception as e:
        print(f"Fehler bei der Verbindung mit {client_address}: {e}")
    finally:
        print(f"Verbindung mit {client_address} geschlossen.")
        client_socket.close()
        del client_name[client_address]

print("Server läuft...")
client_socket, client_address = server.accept()
print("Client verbunden:", client_address)
thread1 = threading.Thread(target=handle_client1, args=(client_socket, client_address))
thread1.start()


client_socket2, client_address2 = server.accept()
print("Client verbunden:", client_address2)
thread2 = threading.Thread(target=handle_client2, args=(client_socket2, client_address2))
thread2.start()




