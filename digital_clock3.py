import tkinter
import time

def my_clock():
    la1["text"] = time.strftime("%X")
    la2["text"] = time.strftime("%Y/%m/%d")
    la3["text"] = time.strftime(" %A ")
    root.after(1000,my_clock)

root = tkinter.Tk()
root.geometry("300x200")
root.resizable(False, False)
root["bg"] = "navy"
la1 = tkinter.Label(bg="navy", fg="skyblue")
la1["font"] = ("Times New Roman",32)
la1.pack()
fr = tkinter.Frame(width=290,height=60, bg="black")
la2 =tkinter.Label(fr,bg="black",fg="gold")
la2["font"] = ("Times New Roman",20)
la2.place(x=20,y=10)
la3 = tkinter.Label(fr,bg="black",fg="lime")
la3["font"] = ("Times New Roman",20)
la3.place(x=180, y=10)
fr.pack()
my_clock
root.mainloop()

