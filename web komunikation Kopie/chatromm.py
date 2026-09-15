import socket
def aktion():
    client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server_ip = "192.168.178.80"
    server_port = 8000


    client.connect((server_ip, server_port))
    nachricht = client.recv(1024).decode("utf-8")
    client.send("chatroom".encode("utf-8"))
    

  

    while True:


        nachricht = client.recv(1024).decode("utf-8")
        print(nachricht)
        client.send("t".encode("utf-8"))
aktion()