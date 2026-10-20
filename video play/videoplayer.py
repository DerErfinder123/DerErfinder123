import tkinter as tk
from tkinter import filedialog
import vlc 
from ctypes import cdll,c_void_
from ctyps.util


def open_video():
    filename = filedialog.askopenfile(filetypes=[("video files", "*.mp4, *.mkv, *.mov, *.avi")])

    print(filename)
    if filename

window = tk.TK()
window.titel("videoplayer")
window.gemotry(800x600)

button = tk.Button(window, text="video öfnen",comand=open_video)
button.pack()
window.mainloop()


