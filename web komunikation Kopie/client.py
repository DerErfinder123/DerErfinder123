import socket
def aktion():
    client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server_ip = "192.168.178.80"
    server_port = 8000

    client.connect((server_ip, server_port))
    print(client.recv(1024).decode("utf-8"))
    nachricht = str(input("name:"))

    #nachricht = str(1234)
    client.send(nachricht.encode("utf-8")[:1024])

    while True:
        nachricht = input("nachricht:")
        #nachricht = str(1234)




        client.send(nachricht.encode("utf-8")[:1024])
        if nachricht == "ende":
            break
    client.close

aktion()