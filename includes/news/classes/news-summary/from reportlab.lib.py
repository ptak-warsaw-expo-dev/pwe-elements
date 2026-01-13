from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas
from reportlab.lib import colors

def clean_text(text):
    # Mapping Polish chars to Latin to ensure PDF compatibility in this environment
    replacements = {
        'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z', 'ż': 'z',
        'Ą': 'A', 'Ć': 'C', 'Ę': 'E', 'Ł': 'L', 'Ń': 'N', 'Ó': 'O', 'Ś': 'S', 'Ź': 'Z', 'Ż': 'Z'
    }
    for pol, lat in replacements.items():
        text = text.replace(pol, lat)
    return text

def create_pdf(filename):
    c = canvas.Canvas(filename, pagesize=A4)
    width, height = A4
    
    # Title
    c.setFont("Helvetica-Bold", 16)
    c.drawString(50, height - 50, clean_text("Raport Roczny: Podsumowanie Ruchu na Stronach"))
    
    y_position = height - 80
    
    reports = [
        {
            "title": "1. Facade Expo",
            "period": "28.11.2024 - 27.11.2025",
            "metrics": [
                "Liczba wyswietlen (Odslony): 79 617",
                "Liczba wejsc (Sesje): 36 499",
                "Sredni czas zaangazowania: ~47 sekund"
            ],
            "countries": [
                "Polska (PL): 21 721 (~94%)",
                "USA (US): 469 (~2%)",
                "Niemcy (DE): 306 (~1.3%)",
                "Litwa (LT): 293 (~1.2%)",
                "Indie (IN): 222 (~1%)"
            ],
            "extra": "Dodatkowe: Rejestracja B2B: 7072, Scroll: 28384"
        },
        {
            "title": "2. Warsaw Medical Expo",
            "period": "28.12.2024 - 27.11.2025",
            "metrics": [
                "Liczba wyswietlen (Odslony): 112 978",
                "Liczba wejsc (Sesje): 46 853",
                "Sredni czas zaangazowania: ~67 sekund"
            ],
            "countries": [
                "Polska (PL): 23 353 (~88%)",
                "Niemcy (DE): 1 049 (~4%)",
                "Ukraina (UA): 900 (~3.4%)",
                "Litwa (LT): 845 (~3.2%)",
                "Lotwa (LV): 451 (~1.7%)"
            ],
            "extra": "Dodatkowe: Rejestracja B2B (PL): 10124, Rejestracja B2B (EN): 2239"
        },
        {
            "title": "3. Poultry Tech",
            "period": "28.11.2024 - 27.11.2025",
            "metrics": [
                "Liczba wyswietlen (Odslony): 40 908",
                "Liczba wejsc (Sesje): 21 930",
                "Sredni czas zaangazowania: ~53 sekundy"
            ],
            "countries": [
                "Polska (PL): 13 937 (~91.3%)",
                "Niemcy (DE): 476 (~3.1%)",
                "USA (US): 431 (~2.8%)",
                "Wlochy (IT): 271 (~1.8%)",
                "Indie (IN): 151 (~1%)"
            ],
            "extra": "Dodatkowe: Rejestracja B2B: 3639, Lead Visitor PL: 1312"
        }
    ]
    
    for report in reports:
        # Check space
        if y_position < 200:
            c.showPage()
            y_position = height - 50
        
        # Section Title
        c.setFont("Helvetica-Bold", 14)
        c.drawString(50, y_position, clean_text(report["title"]))
        y_position -= 20
        
        # Period
        c.setFont("Helvetica-Oblique", 10)
        c.setFillColor(colors.gray)
        c.drawString(50, y_position, clean_text(f"Okres: {report['period']}"))
        c.setFillColor(colors.black)
        y_position -= 25
        
        # Metrics Header
        c.setFont("Helvetica-Bold", 11)
        c.drawString(50, y_position, clean_text("Ruch i Zaangazowanie:"))
        y_position -= 15
        
        # Metrics Body
        c.setFont("Helvetica", 11)
        for metric in report["metrics"]:
            c.drawString(70, y_position, clean_text(f"- {metric}"))
            y_position -= 15
        y_position -= 10
            
        # Countries Header
        c.setFont("Helvetica-Bold", 11)
        c.drawString(50, y_position, clean_text("Zasiegi Miedzynarodowe:"))
        y_position -= 15
        
        # Countries Body
        c.setFont("Helvetica", 11)
        for country in report["countries"]:
            c.drawString(70, y_position, clean_text(f"{country}"))
            y_position -= 15
        y_position -= 10

        # Extra Data
        c.setFont("Helvetica", 9)
        c.setFillColor(colors.darkblue)
        c.drawString(50, y_position, clean_text(report["extra"]))
        c.setFillColor(colors.black)
        
        y_position -= 40 # Space between reports
        c.line(50, y_position + 20, width - 50, y_position + 20)

    c.save()
    return filename

pdf_file = create_pdf("Raport_Ruchu_Stron_2024_2025.pdf")
print(pdf_file)