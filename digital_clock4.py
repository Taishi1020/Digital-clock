import tkinter
import time

def my_clock():
   t = time.strftime("%x")
   la1["text"] = t
   d = time.strftime("%x")
   la2["text"] = d
   root.after(1000,my_clock)

root = tkinter.Tk()
root.geometry("300x120")
la1 = tkinter.Label()
la1["font"] =("Times New Roman",30)
la1.pack()
la2 = tkinter.Label()
la2["font"] = ("Times New Roman",30)
la2.pack()
my_clock()
root.mainloop()