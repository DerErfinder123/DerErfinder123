import socket,threading
socket_zu_name = dict() 
def handle_client(client_socket, client_address):
    try:
        client_socket.send("Hallo!Bitte gib als erstes deinen Namen ein".encode("utf-8"))
        request = client_socket.recv(1024)#1024 Bytes empfangen
        request = str(request.decode("utf-8"))
        socket_zu_name[client_socket] = request
        print(socket_zu_name[client_socket] + "hat den chat betreten")
        client_socket.send("Hallo "+socket_zu_name[client_socket].encode("utf-8"))


        while True: 
            request = client_socket.recv(1024)#1024 Bytes empfangen
            if len(request) == 0:
                break
            request = str(request.decode("utf-8"))


            print(socket_zu_name[client_socket] + " : " + request)
            for socket in socket_zu_name:
                socket.send((socket_zu_name[client_address] + " : " + request).encode("utf-8"))
            if request =="ende":
                break

    except Exception as e:
        print(f"Fehler mit client:{e}")
    finally:
        client_socket.close()

def aktion():
    server = socket.socket(socket.AF_INET,socket.SOCK_STREAM)#ein socket ,der mittels IPv4 und TCP komuniziert
    

    server_ip ="0.0.0.0"
    port = 8000

    server.bind((server_ip,port))

    server.listen()#belibig viele client kaann sich zur gleichen zeit mit dem server verbinden
    print("Server bereit") 
    while True:
        client_socket, client_address = server.accept()
        print(" Neuen client akzeptiert")
        thread = threading.Thread(target =handle_client,args=(client_socket,client_address))
        thread.start()
        #thread.

    
        #
aktion()
    
