import socket,threading
server_ip = "0.0.0.0"
server_port = 8000
anzahl_threads = 0
server = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
server.bind(server_ip,server_port)
server.listen(2)
client_name = dict()
while anzahl_threads < 2:
    client_socket, client_adress = server.accept()


def handle_client(client_socket, client_address):
    client_socket.send("Welcome to the server!gebe namen ein".encode("utf-8"))
    request = client_socket.recv(1024)
    request = request.decode("utf-8")
    client_name[client_address] = request
    while True:
        request = client_socket.recv(1024)
        client_socket.send(client_name[client_address], request.encode("utf-8"))







