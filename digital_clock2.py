import tkinter
import time
import webbrowser

def my_clock():
  t = time.strftime("%X")
  la1["text"] = t
  la2["text"] = time.strftime('%y/%m/%d')
  la3["text"] = time.strftime('%A')
  if(t == en1.get()):
     webbrowser.open(en2.get())
  root.after(1000, my_clock)

root = tkinter.Tk()
root.geometry("800x600")
root.resizable(False,False)
root["bg"] = "navy"
la1 =tkinter.Label(bg="navy",fg="skyblue")
la1["font"] = ("Times New Roman",32)
la1.pack()
fr = tkinter.Frame(width=290,height=60,bg="black")
la2 = tkinter.Label(fr,bg="black",fg="gold")
la2["font"] = ("Times New Roman",20)
la2.place(x=20, y=10)
la3 = tkinter.Label(fr,bg="black",fg="lime")
la3["font"] = la2["font"]
la3.place(x=180, y=10)
fr.pack()
en1 = tkinter.Entry(width=30)
en1["font"] = ("Times New Roman",12)
en1.place(x=20, y=130)
en2 = tkinter.Entry(width=30)
en2["font"] = en1["font"]
en2.place(x=20, y=160)
my_clock
root.mainloop()